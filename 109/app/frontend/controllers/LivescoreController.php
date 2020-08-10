<?php namespace Modules\Frontend\Controllers;

use Modules\PhalconVn\General;

class LivescoreController extends BaseController
{
    public function indexAction()
    {
        $title_bar = $title = $keyword = 'Livescore - Tỷ số bóng đá trực tuyến';
        $description = 'Livescore - Tỷ số bóng đá trực tuyến';

        include('dom/simple_html_dom.php');

        $url = '//ole.vn/livescore.html';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);

        if (!empty($html)) {
            foreach ($html->find('.lsBoxr #lvsc') as $element) {
                $item = (!empty($element->innertext)) ? trim($element->innertext) : null;
                
                $item = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item);
                $item = preg_replace("/<a[^>]+\>/i", '', $item);
                
                if ($item != null) {
                    $livescore['data'] =  $item;
                }
            }
            
              
            foreach ($html->find('.lsBoxr .dateM ul li') as $element) {
                $item_date['date'] = (!empty($element->find('a', 0)->title)) ? trim($element->find('a', 0)->title) : null;
                $item_date['link'] = (!empty($element->find('a', 0)->href)) ? trim($element->find('a', 0)->href) : null;
                $item_date['day'] = (!empty($element->find('a span', 0)->plaintext)) ? trim($element->find('a span', 0)->plaintext) : null;
                $item_date['month'] = (!empty($element->find('a span', 1)->plaintext)) ? trim($element->find('a span', 1)->plaintext) : null;

                if ($item_date != null) {
                    $livescore['date'][] = $item_date;
                }
            }
        }

        $this->view->livescore = $livescore;
        $this->view->title_bar = $title_bar;

        $this->view->title = $title;
        $this->view->keywords = $keyword;
        $this->view->description = $description;
    }

    public function livescoredateAction($date = '')
    {
        $title_bar = $title = $keyword = $description = 'Livescore tỷ số ngày ' . $date;

        include('dom/simple_html_dom.php');

        $url = '//ole.vn/livescore/' . $date . '.html';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);

        if (!empty($html)) {
            foreach ($html->find('.lsBoxr #lvsc') as $element) {
                $item = (!empty($element->innertext)) ? trim($element->innertext) : null;
                
                $item = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item);
                $item = preg_replace("/<a[^>]+\>/i", '', $item);
                
                if ($item != null) {
                    $livescore['data'] =  $item;
                }
            }
            
              
            foreach ($html->find('.lsBoxr .dateM ul li') as $element) {
                $item_date['date'] = (!empty($element->find('a', 0)->title)) ? trim($element->find('a', 0)->title) : null;
                $item_date['link'] = (!empty($element->find('a', 0)->href)) ? trim($element->find('a', 0)->href) : null;
                $item_date['day'] = (!empty($element->find('a span', 0)->plaintext)) ? trim($element->find('a span', 0)->plaintext) : null;
                $item_date['month'] = (!empty($element->find('a span', 1)->plaintext)) ? trim($element->find('a span', 1)->plaintext) : null;

                if ($item_date != null) {
                    $livescore['date'][] = $item_date;
                }
            }
        }

        $this->view->date = $date;
        $this->view->livescore = $livescore;
        $this->view->title_bar = $title_bar;

        $this->view->title = $title;
        $this->view->keywords = $keyword;
        $this->view->description = $description;
    }

    public function standingAction()
    {
        $title_bar = 'Bảng xếp hạng bóng đá';
        
        $this->view->title_bar = $title_bar;
        // $this->view->pick('news/index');
    }
    
    public function standingLeagueAction($league)
    {
        include('dom/simple_html_dom.php');
        
        $general= new General();
        switch ($league) {
            case 'bang-xep-hang-ngoai-hang-anh-anh':
                $folder = 'anh';
                $title_bar = $keyword = 'Bảng xếp hạng Ngoại hạng Anh';
                $title = 'Bảng xếp hạng Ngoại hạng Anh mùa giải 2015-2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của giải Ngoại hạng Anh';
                break;
            
            case 'bang-xep-hang-vdqg-tay-ban-nha-tbn':
                $folder = 'tay-ban-nha';
                $title_bar = 'Bảng xếp hạng VĐQG Tây Ban Nha Laliga';
                $title = 'Bảng xếp hạng Ngoại hạng Anh mùa giải 2015-2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của giải VĐQG Tây Ban Nha Laliga';
                break;
            
            case 'bang-xep-hang-vdqg-duc-duc':
                $folder = 'duc';
                $title_bar = $keyword = 'Bảng xếp hạng VĐQG Đức Bundesliga';
                $title = 'Bảng xếp hạng VĐQG Đức Bundesliga mùa giải 2015-2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của giải VĐQG Đức Bundesliga';
                break;
            
            case 'bang-xep-hang-vdqg-italia-ita':
                $folder = 'italia';
                $title_bar = $keyword = 'Bảng xếp hạng VĐQG Italia Seria A';
                $title = 'Bảng xếp hạng VĐQG Italia Seria A mùa giải 2015-2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của giải VĐQG Italia Serie A';
                break;
            
            case 'bang-xep-hang-vdqg-phap-pha':
                $folder = 'phap';
                $title_bar = $keyword = 'Bảng xếp hạng VĐQG Pháp Ligue 1';
                $title = 'Bảng xếp hạng VĐQG Pháp Ligue 1 mùa giải 2015-2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của giải VĐQG Pháp Ligue 1';
                break;
            
            case 'bang-xep-hang-vdqg-viet-nam-vqg':
                $folder = 'viet-nam';
                $title_bar = $keyword = 'Bảng xếp hạng V-league';
                $title = 'Bảng xếp hạng V-league mùa giải 2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của giải VĐQG Việt Nam V-league';
                break;

            case 'bang-xep-hang-euro-2016':
                $title_bar = $keyword = 'Bảng xếp hạng Euro 2016';
                $title = 'Bảng xếp hạng Euro 2016';
                $description = 'Trân trọng giới thiệu tới độc giả bảng xếp hạng của Bảng xếp hạng Euro 2016';

                $url = '//bongda.wap.vn/bang-xep-hang-euro-2016-505.html';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);

                if (!empty($html)) {
                    $schedule_date = array();

                    foreach ($html->find('.nen_bd') as $element) {
                        $item = (!empty($element->find('table', 0)->innertext)) ? trim($element->find('table', 0)->innertext) : null;

                        if ($item != null) {
                            $standing_euro = preg_replace("/<a[^>]+\>/i", "", $item);
                        }
                    }
                }
                $standing_euro = str_replace("Chi tiết", "", $standing_euro);
                $this->view->standing_euro = $standing_euro;
                $this->view->pick('livescore/standingEuro');
                break;
            
            default:
                $folder = null;
                $title = null;
                $keyword = null;
                $description = null;
        }

        $base_url = '//bongda.wap.vn/';
        $sub_img_url = '//static.bongda.wap.vn/team-logo/' . $folder . '/';
        $url = $base_url . $league . '.html';
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);
        
        if (!empty($html)) {
            $standings = array();
            foreach ($html->find('.New_col-centre>div>table>tr') as $article) {
                $item['xh'] = (!empty($article->find('td.bxh_gd_1', 0)->plaintext)) ? trim($article->find('td.bxh_gd_1', 0)->plaintext) : null;
                $item['team_name'] = (!empty($article->find('td.bxh_gd_2', 0)->plaintext)) ? trim($article->find('td.bxh_gd_2', 0)->plaintext) : null;
                $item['team_link'] = (!empty($article->find('td.bxh_gd_2 a', 0)->href)) ? trim($base_url . $article->find('td.bxh_gd_2 a', 0)->href) : null;
                $item['game_number'] = (!empty($article->find('td.bxh_gd_1', 1)->plaintext)) ? trim($article->find('td.bxh_gd_1', 1)->plaintext) : null;
                $item['win'] = (!empty($article->find('td.bxh_gd_1', 2)->plaintext)) ? trim($article->find('td.bxh_gd_1', 2)->plaintext) : null;
                $item['d'] = (!empty($article->find('td.bxh_gd_1', 3)->plaintext)) ? trim($article->find('td.bxh_gd_1', 3)->plaintext) : null;
                $item['lost'] = (!empty($article->find('td.bxh_gd_1', 4)->plaintext)) ? trim($article->find('td.bxh_gd_1', 4)->plaintext) : null;
                $item['gs'] = (!empty($article->find('td.bxh_gd_1', 5)->plaintext)) ? trim($article->find('td.bxh_gd_1', 5)->plaintext) : null;
                $item['ga'] = (!empty($article->find('td.bxh_gd_1', 6)->plaintext)) ? trim($article->find('td.bxh_gd_1', 6)->plaintext) : null;
                $item['offsets'] = (!empty($article->find('td.bxh_gd_1', 7)->plaintext)) ? trim($article->find('td.bxh_gd_1', 7)->plaintext) : null;
                $item['mark'] = (!empty($article->find('td.bxh_gd_1', 8)->plaintext)) ? trim($article->find('td.bxh_gd_1', 8)->plaintext) : null;
                (!empty($item['team_link'])) ? $item['logo'] = $sub_img_url . $general->create_slug_second($item['team_name']) . '.gif' : null ;

                
                if ($item['xh'] != null) {
                    $standings[] = $item;
                }
            }
            
            
            
            $standings = array_slice($standings, 1, 20);
            if ($league == 'bang-xep-hang-vdqg-duc-duc') {
                $standings = array_slice($standings, 1, 17);
            }
            if ($league == 'bang-xep-hang-vdqg-viet-nam-vqg') {
                $standings = array_slice($standings, 0, 14);
            }

            $standings_cl = array();
            foreach ($html->find('.New_col-centre div table tr.bg_C1') as $article) {
                $item_cl = (!empty($article->find('td.bxh_gd_1', 0)->plaintext)) ? trim($article->find('td.bxh_gd_1', 0)->plaintext) : null;
                if ($item_cl != null) {
                    $standings_cl[] = $item_cl;
                }
            }

            $standings_vlcl = array();
            foreach ($html->find('.New_col-centre div table tr.bg_VLC1') as $article) {
                $item_vlcl = (!empty($article->find('td.bxh_gd_1', 0)->plaintext)) ? trim($article->find('td.bxh_gd_1', 0)->plaintext) : null;
                if ($item_vlcl != null) {
                    $standings_vlcl[] = $item_vlcl;
                }
            }
            
            $standings_c2 = array();
            foreach ($html->find('.New_col-centre div table tr.bg_C2') as $article) {
                $item_c2 = (!empty($article->find('td.bxh_gd_1', 0)->plaintext)) ? trim($article->find('td.bxh_gd_1', 0)->plaintext) : null;
                if ($item_c2 != null) {
                    $standings_c2[] = $item_c2;
                }
            }

            $standings_xh = array();
            foreach ($html->find('.New_col-centre div table tr.bg_XH') as $article) {
                $item_xh = (!empty($article->find('td.bxh_gd_1', 0)->plaintext)) ? trim($article->find('td.bxh_gd_1', 0)->plaintext) : null;
                if ($item_xh != null) {
                    $standings_xh[] = $item_xh;
                }
            }

            //echo '<pre>'; print_r($standings); echo '</pre>';die;
        }

        $this->view->standings = $standings;
        $this->view->standings_cl = $standings_cl;
        $this->view->standings_vlcl = $standings_vlcl;
        $this->view->standings_c2 = $standings_c2;
        $this->view->standings_xh = $standings_xh;
        $this->view->title_bar = $title_bar;

        $this->view->title = $title;
        $this->view->keywords = $keyword;
        $this->view->description = $description;
    }
    
    public function scheduleAction()
    {
        $title_bar = 'Lịch thi đấu bóng đá';
        
        $this->view->title_bar = $title_bar;
        // $this->view->pick('news/index');
    }

    public function scheduleLeagueAction($league)
    {
        $general= new General();
        include('dom/simple_html_dom.php');

        switch ($league) {
            case 'lich-thi-dau-vleague-2016':
                $title_bar = $title = $keyword = 'Lịch thi đấu Vleague 2016';
                $description = 'Trân trọng giới thiệu tới độc giả Lịch thi đấu Vleague 2016';
                $url = '//www.vnleague.com/vdqg-vleague/ket-qua/';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);
                if (!empty($html)) {
                    foreach ($html->find('div.detail > table > tbody') as $element) {
                        $item['round'] = (!empty($element->find('tr td.mess', 1)->plaintext)) ? trim($element->find('tr td.mess', 1)->plaintext) : null;

                        if ($item['round'] != null) {
                            $schedule_info = $item;
                        }
                    }

                    foreach ($html->find('tr.bg_trang') as $element) {
                        $array['hour'] = (!empty($element->find('td', 1)->plaintext)) ? trim($element->find('td', 1)->plaintext) : null;
                        $array['date'] = (!empty($element->find('td', 2)->plaintext)) ? trim($element->find('td', 2)->plaintext) : null;
                        $array['house'] = (!empty($element->find('td', 3)->plaintext)) ? trim($element->find('td', 3)->plaintext) : null;
                        $array['guest'] = (!empty($element->find('td', 5)->plaintext)) ? trim($element->find('td', 5)->plaintext) : null;
                        $array['chanel'] = (!empty($element->find('td', 6)->plaintext)) ? trim($element->find('td', 6)->plaintext) : null;

                        if ($array['date'] != null) {
                            $schedule_info['info'][] = $array;
                        }
                    }
                }
                $round = str_replace('Kết quả', '', $schedule_info['round']);

                $this->view->round = $round;
                $this->view->pick('livescore/scheduleVleague');
                break;

            case 'lich-thi-dau-ngoai-hang-anh':
                $title_bar = $title = $keyword = 'Lịch thi đấu giải bóng đá Ngoại hạng Anh';
                $description = 'Trân trọng giới thiệu tới độc giả Lịch thi đấu giải bóng đá Ngoại hạng Anh';
                $url = '//www.goal.com/en/fixtures/premier-league/8';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);
                if (!empty($html)) {
                    $all_match = array();

                    foreach ($html->find('table.match-table') as $t) {
                        $item['day'] = trim($t->find('tr.subheader th.comp-date', 0)->plaintext);
                        $item['date'] = date('Y-m-d', strtotime($item['day']));
                
                        
                        foreach ($t->find('tr.clickable') as $element) {
                            $time = trim($element->find('td.status', 0)->plaintext);
                            $date_time = (preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $time)) ? $general->change_timezone($item['date'] . ' '. $time) : $item['date'] . ' '. $time;
                            $date_time = explode(' ', $date_time);

                            $item_match['date'] = $date_time[0];
                            $item_match['time'] = $date_time[1];
                            $item_match['team_home'] = trim($element->find('td.team div.home span', 0)->plaintext);
                            $item_match['team_home_logo'] = trim($element->find('td.team div.home img', 0)->src);
                            $item_match['team_away'] = trim($element->find('td.team div.away span', 0)->plaintext);
                            $item_match['team_away_logo'] = trim($element->find('td.team div.away img', 0)->src);
                            $item_match['match_detail'] = '//goal.com' . trim($element->find('td.match-centre a.match-btn', 0)->href);
                            
                            $all_match[] = $item_match;
                        }
                    }

                    $schedule_info = array();
                    if (!empty($all_match)) {
                        foreach ($all_match as $key => $item) {
                            $date = $general->translate_day(date('l', strtotime($item['date']))) . ', ' . date('d/m/Y', strtotime($item['date']));
                            $schedule_info[$date][$key] = $item;
                        }
                    }

                    $this->view->pick('livescore/schedule2016');
                }
                break;

            case 'lich-thi-dau-laliga':
                $title_bar = $title = $keyword = 'Lịch thi đấu giải VĐQG Tây Ban Nha Laliga';
                $description = 'Trân trọng giới thiệu tới độc giả Lịch thi đấu giải VĐQG Tây Ban Nha Laliga';
                $url = '//www.goal.com/en/fixtures/primera-divisi%C3%B3n/7';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);
                if (!empty($html)) {
                    $all_match = array();

                    foreach ($html->find('table.match-table') as $t) {
                        $item['day'] = trim($t->find('tr.subheader th.comp-date', 0)->plaintext);
                        $item['date'] = date('Y-m-d', strtotime($item['day']));
                
                        
                        foreach ($t->find('tr.clickable') as $element) {
                            $time = trim($element->find('td.status', 0)->plaintext);
                            $date_time = (preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $time)) ? $general->change_timezone($item['date'] . ' '. $time) : $item['date'] . ' '. $time;
                            $date_time = explode(' ', $date_time);

                            $item_match['date'] = $date_time[0];
                            $item_match['time'] = $date_time[1];
                            $item_match['team_home'] = trim($element->find('td.team div.home span', 0)->plaintext);
                            $item_match['team_home_logo'] = trim($element->find('td.team div.home img', 0)->src);
                            $item_match['team_away'] = trim($element->find('td.team div.away span', 0)->plaintext);
                            $item_match['team_away_logo'] = trim($element->find('td.team div.away img', 0)->src);
                            $item_match['match_detail'] = '//goal.com' . trim($element->find('td.match-centre a.match-btn', 0)->href);
                            
                            $all_match[] = $item_match;
                        }
                    }

                    $schedule_info = array();
                    if (!empty($all_match)) {
                        foreach ($all_match as $key => $item) {
                            $date = $general->translate_day(date('l', strtotime($item['date']))) . ', ' . date('d/m/Y', strtotime($item['date']));
                            $schedule_info[$date][$key] = $item;
                        }
                    }

                    $this->view->pick('livescore/schedule2016');
                }
                break;

            case 'lich-thi-dau-bundesliga':
                $title_bar = $title = $keyword = 'Lịch thi đấu giải VĐQG Đức Bundesliga';
                $description = 'Trân trọng giới thiệu tới độc giả Lịch thi đấu giải VĐQG Đức Bundesliga';
                $url = '//www.goal.com/en/fixtures/bundesliga/9';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);
                if (!empty($html)) {
                    $all_match = array();

                    foreach ($html->find('table.match-table') as $t) {
                        $item['day'] = trim($t->find('tr.subheader th.comp-date', 0)->plaintext);
                        $item['date'] = date('Y-m-d', strtotime($item['day']));
                
                        
                        foreach ($t->find('tr.clickable') as $element) {
                            $time = trim($element->find('td.status', 0)->plaintext);
                            $date_time = (preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $time)) ? $general->change_timezone($item['date'] . ' '. $time) : $item['date'] . ' '. $time;
                            $date_time = explode(' ', $date_time);

                            $item_match['date'] = $date_time[0];
                            $item_match['time'] = $date_time[1];
                            $item_match['team_home'] = trim($element->find('td.team div.home span', 0)->plaintext);
                            $item_match['team_home_logo'] = trim($element->find('td.team div.home img', 0)->src);
                            $item_match['team_away'] = trim($element->find('td.team div.away span', 0)->plaintext);
                            $item_match['team_away_logo'] = trim($element->find('td.team div.away img', 0)->src);
                            $item_match['match_detail'] = '//goal.com' . trim($element->find('td.match-centre a.match-btn', 0)->href);
                            
                            $all_match[] = $item_match;
                        }
                    }

                    $schedule_info = array();
                    if (!empty($all_match)) {
                        foreach ($all_match as $key => $item) {
                            $date = $general->translate_day(date('l', strtotime($item['date']))) . ', ' . date('d/m/Y', strtotime($item['date']));
                            $schedule_info[$date][$key] = $item;
                        }
                    }

                    $this->view->pick('livescore/schedule2016');
                }
                break;

            case 'lich-thi-dau-serie-a':
                $title_bar = $title = $keyword = 'Lịch thi đấu giải VĐQG Italia Serie A';
                $description = 'Trân trọng giới thiệu tới độc giả Lịch thi đấu giải VĐQG Italia Serie A';
                $url = '//www.goal.com/en/fixtures/serie-a/13';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);
                if (!empty($html)) {
                    $all_match = array();

                    foreach ($html->find('table.match-table') as $t) {
                        $item['day'] = trim($t->find('tr.subheader th.comp-date', 0)->plaintext);
                        $item['date'] = date('Y-m-d', strtotime($item['day']));
                
                        
                        foreach ($t->find('tr.clickable') as $element) {
                            $time = trim($element->find('td.status', 0)->plaintext);
                            $date_time = (preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $time)) ? $general->change_timezone($item['date'] . ' '. $time) : $item['date'] . ' '. $time;
                            $date_time = explode(' ', $date_time);

                            $item_match['date'] = $date_time[0];
                            $item_match['time'] = $date_time[1];
                            $item_match['team_home'] = trim($element->find('td.team div.home span', 0)->plaintext);
                            $item_match['team_home_logo'] = trim($element->find('td.team div.home img', 0)->src);
                            $item_match['team_away'] = trim($element->find('td.team div.away span', 0)->plaintext);
                            $item_match['team_away_logo'] = trim($element->find('td.team div.away img', 0)->src);
                            $item_match['match_detail'] = '//goal.com' . trim($element->find('td.match-centre a.match-btn', 0)->href);
                            
                            $all_match[] = $item_match;
                        }
                    }

                    $schedule_info = array();
                    if (!empty($all_match)) {
                        foreach ($all_match as $key => $item) {
                            $date = $general->translate_day(date('l', strtotime($item['date']))) . ', ' . date('d/m/Y', strtotime($item['date']));
                            $schedule_info[$date][$key] = $item;
                        }
                    }

                    $this->view->pick('livescore/schedule2016');
                }
                break;

            case 'lich-thi-dau-ligue-1':
                $title_bar = $title = $keyword = 'Lịch thi đấu giải VĐQG Pháp Ligue 1';
                $description = 'Trân trọng giới thiệu tới độc giả Lịch thi đấu giải VĐQG Italia Ligue 1';
                $url = '//www.goal.com/en/fixtures/ligue-1/16';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $curl_html = curl_exec($ch);
                curl_close($ch);

                $html = str_get_html($curl_html);
                if (!empty($html)) {
                    $all_match = array();

                    foreach ($html->find('table.match-table') as $t) {
                        $item['day'] = trim($t->find('tr.subheader th.comp-date', 0)->plaintext);
                        $item['date'] = date('Y-m-d', strtotime($item['day']));
                
                        
                        foreach ($t->find('tr.clickable') as $element) {
                            $time = trim($element->find('td.status', 0)->plaintext);
                            $date_time = (preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $time)) ? $general->change_timezone($item['date'] . ' '. $time) : $item['date'] . ' '. $time;
                            $date_time = explode(' ', $date_time);

                            $item_match['date'] = $date_time[0];
                            $item_match['time'] = $date_time[1];
                            $item_match['team_home'] = trim($element->find('td.team div.home span', 0)->plaintext);
                            $item_match['team_home_logo'] = trim($element->find('td.team div.home img', 0)->src);
                            $item_match['team_away'] = trim($element->find('td.team div.away span', 0)->plaintext);
                            $item_match['team_away_logo'] = trim($element->find('td.team div.away img', 0)->src);
                            $item_match['match_detail'] = '//goal.com' . trim($element->find('td.match-centre a.match-btn', 0)->href);
                            
                            $all_match[] = $item_match;
                        }
                    }

                    $schedule_info = array();
                    if (!empty($all_match)) {
                        foreach ($all_match as $key => $item) {
                            $date = $general->translate_day(date('l', strtotime($item['date']))) . ', ' . date('d/m/Y', strtotime($item['date']));
                            $schedule_info[$date][$key] = $item;
                        }
                    }

                    $this->view->pick('livescore/schedule2016');
                }
                break;
            

            default:
                $title_bar = null;
                $title = null;
                $keyword = null;
                $description = null;
        }
        
        //echo '<pre>'; print_r($schedule_info); echo '</pre>';die;
        $this->view->schedule_info = $schedule_info;
        $this->view->title_bar = $title_bar;

        $this->view->title = $title;
        $this->view->keywords = $keyword;
        $this->view->description = $description;
    }

    public function rateAction()
    {
        $title_bar = $title = $keyword = 'Tỷ lệ kèo bóng đá';
        $description = 'Tỷ lệ kèo bóng đá';

        include('dom/simple_html_dom.php');

        $url = '//bongda.wap.vn/ty-le-bong-da.html';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);

        if (!empty($html)) {
            foreach ($html->find('.menu_tyle a') as $element) {
                $link = (!empty($element->href)) ? trim($element->href) : null;
                $item_date['day'] = (!empty($element->plaintext)) ? trim($element->plaintext) : null;
                $item_date['class'] = (!empty($element->class)) ? trim($element->class) : null;

                if (!empty($link)) {
                    $item_date['link'] = str_replace('.html', '', $link);
                }

                if ($item_date != null) {
                    $date_link[] = $item_date;
                }
            }

            foreach ($html->find('.New_col-centre') as $element) {
                $item = (!empty($element->innertext)) ? trim($element->innertext) : null;

                $item = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item);
                $item = preg_replace("/<a[^>]+\>/i", '', $item);
                $item = str_replace('| Phong độ', '', $item);
                $item = str_replace('KEO BONG DA TRUC TUYEN', 'KÈO BÓNG ĐÁ TRỰC TUYẾN', $item);
                $item = str_replace('KEO BONG DA HOM NAY', 'KÈO BÓNG ĐÁ HÔM NAY', $item);

                if ($item != null) {
                    $rate_info =  $item;
                }
            }
        }

        $date_link = array_slice($date_link, 1, count($date_link) - 1);
        //echo "<pre>"; print_r($date_link); echo '</pre>';die;

        $this->view->date_link = $date_link;
        $this->view->rate_info = $rate_info;
        $this->view->title_bar = $title_bar;

        $this->view->title = $title;
        $this->view->keywords = $keyword;
        $this->view->description = $description;
    }


    public function ratedateAction($date)
    {
        $title_bar = $title = $keyword = 'Tỷ lệ kèo bóng đá';
        $description = 'Tỷ lệ kèo bóng đá';

        include('dom/simple_html_dom.php');

        $url = '//bongda.wap.vn/' . $date . '.html';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);

        if (!empty($html)) {
            foreach ($html->find('.menu_tyle a') as $element) {
                $link = (!empty($element->href)) ? trim($element->href) : null;
                $link = preg_replace("/<a[^>]+\>/i", '', $link);
                $link = str_replace('</a>', '', $link);
                $item_date['day'] = (!empty($element->plaintext)) ? trim($element->plaintext) : null;
                $item_date['class'] = (!empty($element->class)) ? trim($element->class) : null;

                if (!empty($link)) {
                    $item_date['link'] = str_replace('.html', '', $link);
                }

                if ($item_date != null) {
                    $date_link[] = $item_date;
                }
            }

            foreach ($html->find('.New_col-centre') as $element) {
                $item = (!empty($element->innertext)) ? trim($element->innertext) : null;

                $item = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item);
                $item = preg_replace("/<a[^>]+\>/i", '', $item);
                $item = str_replace('| Phong độ', '', $item);
                $item = str_replace('KEO BONG DA TRUC TUYEN', 'KÈO BÓNG ĐÁ TRỰC TUYẾN', $item);
                $item = str_replace('KEO BONG DA HOM NAY', 'KÈO BÓNG ĐÁ HÔM NAY', $item);

                if ($item != null) {
                    $rate_info =  $item;
                }
            }
        }

        //echo "<pre>"; print_r($date_link); echo '</pre>';die;

        $this->view->date_link = $date_link;
        $this->view->rate_info = $rate_info;
        $this->view->title_bar = $title_bar;

        $this->view->title = $title;
        $this->view->keywords = $keyword;
        $this->view->description = $description;
        $this->view->pick('livescore/rate');
    }
}
