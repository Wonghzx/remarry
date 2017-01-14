<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/weChatlib/WxPay.Api.php';

class Test extends CI_Controller
{

    private $ci;

    private static $setNickname;

    private $nickname;


    function __construct()
    {
        parent::__construct();
        $this->nickname = '15217162610';
        $this->tarname = 'L';
        $this->password = md5("7869057");
        $this->load->model('Member_Models');
    }


    public function index()
    {

        if (date('y-j') > date('y-j', 1483585196)){
            echo 12;
        } else {
            echo 2345;
        }
    }

    public function curlFileGetContents($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

}
