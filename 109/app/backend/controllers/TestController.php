<?php namespace Modules\Backend\Controllers;

use Modules\Models\Domain;
use Modules\Models\Setting;
use Modules\Models\Subdomain;
use Modules\Models\TmpSubdomainUser;
use Modules\Forms\AdminForm;
use Modules\Forms\SignUpForm;
use Modules\Forms\ForgotPasswordForm;
use Modules\Auth\Auth;
use Modules\Auth\Exception as AuthException;
use Modules\Models\Users;
use Modules\Models\ResetPasswords;
use Modules\Models\Layout;
use Modules\Models\LayoutConfig;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Modules\PhalconVn\General;
use Modules\Mail\MyPHPMailer;
use Statickidz\GoogleTranslate;
use Phalcon\Translate\Adapter\NativeArray;
use Modules\Models\Languages;
use Modules\Models\Banner;
use Modules\Models\SubdomainRating;
use MatthiasMullie\Minify;

class TestController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->_get_subdomainID();
        $this->_cacheKey = $this->_subdomain_id . '-admin-index';
    }

    /**
     * Test minify css, js
     */
    public function testMinifyAction()
    {
    	$this->view->disable();
    	$sourcePath = 'assets/css/pages/100/style2.css';
		$minifier = new Minify\CSS($sourcePath);

		// save minified file to disk
		$minifiedPath = 'assets/css/pages/100/style.min.css';
		$minifier->minify($minifiedPath);
	}

    public function testShortcodeAction()
    {
        $this->shortcode->addShortcode('test_shortcode');
        $shortCodeEx1 = $this->shortcode->doShortCode('[test_shortcode]');
        echo $shortCodeEx1;
        $this->view->disable();
    }

    public function testBannerAction()
    {
        $bannerCopies = Banner::findFirstById(1);
        echo '<pre>';
        print_r($bannerCopies->toArray());
        echo '</pre>';
        $this->view->disable();
    }

    protected function getTranslation()
    {
        $translations = json_decode(
            file_get_contents('messages/vi.json'),
            true
        );

        // Return a translation object $messages comes from the require
        // statement above
        return new NativeArray(
            [
                'content' => $translations,
            ]
        );
    }

    public function index01Action()
    {
        $this->view->disable();
        /*$t  = $this->locale->getTranslator('vi');
        echo '<pre>';
        print_r($t);
        echo '</pre>';
        $name = 'N';
        echo $t->_('name_is_required');*/
    }

    public function testImageAction()
    {
        $this->view->disable();
        $file = $this->image_service->createThumb('files/product/100/24-03-2018/product_1.jpg');
        echo '<pre>'; print_r($file); echo '</pre>';
    }

    public function convertToEnAction()
    {
        $this->view->disable();
        $trans = new GoogleTranslate();
        $messages = json_decode(file_get_contents('messages/vi.json'));
        $entMessages = [];
        foreach ($messages as $key => $message) {
            $entMessages[$key] = $trans->translate('vi', 'en', $message);
        }

        file_put_contents('messages/en.json', json_encode($entMessages, JSON_UNESCAPED_UNICODE));
        echo '<pre>';
        print_r($entMessages);
        echo '</pre>';
    }

    public function addLangMessageAction()
    {
        $this->view->disable();
        $trans = new GoogleTranslate();
        $languages = Languages::find();
        $msg = 'Hiện chưa có phản hồi nào';
        foreach ($languages as $language) {
            if (file_exists('messages/' . $language->code . '.json')) {
                $messages = json_decode(file_get_contents('messages/' . $language->code . '.json'));
                $langMessages = [];
                foreach ($messages as $key => $message) {
                    $langMessages[$key] = $message;
                }

                if ($language->code != 'vi' && $language->code != 'en') {
                    $langMessages['not_comment_yet'] = $trans->translate('en', $language->code, 'There are no comments yet');
                }

                file_put_contents('messages/' . $language->code . '.json', json_encode($langMessages, JSON_UNESCAPED_UNICODE));
            }
        }
    }

    public function translateAction()
    {
        $this->view->disable();
        $source = 'vi';
        $target = 'en';
        $text = 'Cảm ơn';

        $trans = new GoogleTranslate();
        $result = $trans->translate($source, $target, $text);

        echo $result;
        die;
    }

    public function sendMailAction()
    {
        $mail = new MyPHPMailer();
        $params = [
            'name' => 'đơn hàng',
            'url' => '//118.dev'
        ];
        $mail->send('congngotn@gmail.com', 'Thông tin đơn hàng', 'Đơn hàng mới từ 110.vn', $params);
        $this->view->disable();
    }

    public function optimizeImageAction() {
        $source_urls = PROJECT_PATH . '/public/files/*/images/*';
        foreach (glob($source_urls) as $source_url) {
            $maxSizeOptimize = 1024*1024;
            if (filesize($source_url) > $maxSizeOptimize) {
                $info = getimagesize($source_url);
                if ($info['mime'] == 'image/jpeg') {
                    $image = imagecreatefromjpeg($source_url);
                }

                elseif ($info['mime'] == 'image/gif') {
                    $image = imagecreatefromgif($source_url);
                }

                elseif ($info['mime'] == 'image/png') {
                    $image = imagecreatefrompng($source_url);
                }
                imagejpeg($image, $source_url, 40);
            }
        }
    }
}
