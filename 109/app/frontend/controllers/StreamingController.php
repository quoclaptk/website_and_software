<?php namespace Modules\Frontend\Controllers;

use Modules\Models\Channel;
use Modules\Models\Streaming;
use Modules\PhalconVn\General;
use Phalcon\Mvc\Model\Query;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class StreamingController extends BaseController
{
    public function indexAction($page = 1)
    {
        $title_bar = 'Lịch trực tiếp bóng đá';
        $currentPage =  $page;
        $date_time = date('Y-m-d H:i:s', strtotime('-2 hours', time()));
        $current_time = date('Y-m-d H:i:s', strtotime('+2 hours', time()));
        $date = new \DateTime($date_time);
        $date->add(new \DateInterval('PT3H'));
        $time_add = $date->format("Y-m-d H:i:s");

        $streaming = $this->modelsManager->createBuilder()
            ->columns('streaming.*, leagues.name league_name')
            ->addFrom('Modules\Models\Streaming', 'streaming')
            ->leftJoin('Modules\Models\Leagues', 'leagues.id = streaming.league_id', 'leagues')
            ->where('streaming.active="Y" AND date_time > "'.$date_time.'"')
            ->orderBy('streaming.date_time ASC')
            ->getQuery()
            ->execute();

        $paginator   = new PaginatorModel(
            array(
                "data"  => $streaming,
                "limit" => 45,
                "page"  => $currentPage
            )
        );

        $this->view->current_time = $current_time;
        $this->view->title_bar = $title_bar;
        $this->view->streaming = $streaming;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = 'truc-tiep';
    }

    public function detailAction($stream_link)
    {
        include('dom/simple_html_dom.php');
        $detail = $this->modelsManager->createBuilder()
            ->columns('streaming.*, leagues.name league_name')
            ->addFrom('Modules\Models\Streaming', 'streaming')
            ->leftJoin('Modules\Models\Leagues', 'leagues.id = streaming.league_id', 'leagues')
            ->where('streaming.active="Y" AND link_stream = "'. $stream_link .'"')
            ->getQuery()
            ->getSingleResult();

        if (empty($detail)) {
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }

        $id = $detail->streaming->id;
        $hits = $detail->streaming->hits + 1;

        $phql   = "UPDATE Modules\Models\Streaming SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }

        $list_channel = $this->modelsManager->createBuilder()
            ->columns('id, type, name, slug, iframe, hits,bitrate')
            ->addFrom('Modules\Models\Channel', 'channel')
            ->leftJoin('Modules\Models\TmpChannelStreaming', 'tmp_channel_streaming.channel_id = channel.id', 'tmp_channel_streaming')
            ->where('tmp_channel_streaming.streaming_id = '. $id .'')
            ->orderBy('name ASC')
            ->getQuery()
            ->execute();
        $channel_id = $detail->streaming->channel_id;
        $channel = Channel::findFirst(
            array(
                "conditions" => "id=$channel_id"
            )
        );

        $sopcast = array();
        $url = $detail->streaming->link_sopcast;
        if (!empty($url)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $curl_html = curl_exec($ch);
            curl_close($ch);

            $html = str_get_html($curl_html);

            if (!empty($html)) {
                foreach ($html->find('#list_channel ul li.chanel_sopcast') as $element) {
                    $array['name'] = (!empty($element->find('.link .name a', 0)->plaintext)) ? trim($element->find('.link .name a', 0)->plaintext) : null;
                    $array['link'] = (!empty($element->find('.link .name a', 0)->href)) ? trim($element->find('.link .name a', 0)->href) : null;
                    $array['bitrate'] = (!empty($element->find('.colinfo .bitrate', 0)->plaintext)) ? trim($element->find('.colinfo .bitrate', 0)->plaintext) : null;

                    if ($array['name'] != null) {
                        $sopcast[] = $array;
                    }
                }
            }
        }

        $count_channel = count($list_channel);

        $this->view->title = 'Trực tiếp ' . $detail->streaming->house_team . ' vs ' . $detail->streaming->guest_team . ' ' . $detail->league_name;

        $this->view->channel = $channel;
        $this->view->count_channel = $count_channel;
        $this->view->list_channel = $list_channel;
        $this->view->sopcast = $sopcast;
        $this->view->league_name = $detail->league_name;
        $this->view->item = $detail->streaming;
        $this->view->created_at = date('H:i:s d/m/Y', strtotime($detail->created_at));
    }
}
