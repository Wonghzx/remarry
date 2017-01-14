<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 支付接口
 * @author 842687571@qq.com
 *
 */
class Alipay extends CI_Controller
{
    private $product = [
        'vip_30' => 10.00,
        'vip_180' => 50.00,
        'vip_365' => 100.00,
    ];

    private $appId;

    private $appKey;

    function __construct()
    {
        parent::__construct();
        $this->appId = $this->config->item('appId');
        $this->appKey = $this->config->item('key');
        $this->load->model('alipay_model');

    }

    public function index()
    {

        $rsa_private = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALmC15MgLbXpeeGX193CU+QeAS5Ptzq2heieH//InIv+4cbZqoXZLdMbxan0mQ1iBv9XS921RF8IEPC+rgQxdmp3qc85nSt2MvVDUgbBOssxahdrPXQn/rYXu9XGd5B/2tjkTVPUlUURxJ8XrHK7fJeaqAWpaLNiHxF+X6ZXnmVnAgMBAAECgYB2QKGKd4ir3RiEZXaFNcqkLzwxeT8jfhX+Ik3jjs27r83cJAQ/LiG34IwvumuVzFcJjWwe992NdmbWD7Z9lcoVHCeN6beEKrlz1uO4M8HEPoJ8yCJ0/ub7jda5O/Ddv5F5UG4fuBB2MTPDFcMYpg7QfbconD5J7X0hmLxHnKszAQJBAPZQQMKKtMnUn27tuJongyCHFSS4IZRTIpRqrx5njuSyQ5s2tE1N/HrvtbwDHubaTvUNhxlAQYFnzIWXs75cRHkCQQDAznbtFkzkRQxK4k0HP0Flp+pikRsS9aKuP1x3wvrvrfhmKaSv3mJdgusQhQ9WgAZ8krW+GYEAu+Ia0PdABMDfAkA9KvLaHP4GfTHWp1xHk/ZhVopuovdb0UVuHAw+/bKjoo1ddzlRVUOU+ABmn1PGOoKPInvhTm62ByPoLSMq69jpAkB9i3Mg+i5jTRquACFMIMJCoU4blITemZeugo+BZDLlspBWZbNY2SOP5FmPzjSojICsyRMSj6TSh4S5FWyKAQ5dAkEAzqpRKwv6cemOurs9TQ0RFvA/Nh/qobxRGaH9tp/SKl1rdeIG/mq7Tts4oc1b9pC2oYoBnwgB7uR9zFZUAozd5g==";

        echo $rsa_private;

    }

    function log_result($log_type, $word)
    {
        $data = array(
            'LogType' => $log_type,
            'CreatedAt' => time(),
            'Log' => strftime("%Y-%m-%d-%H：%M：%S", time()) . "\n" . $word . "\n\n"
        );
    }

    public function createOrder()
    {
        //买什么、谁买、购买时间、多少钱、订单号、订单状态（ -1 0 1  ）
        list($usec, $sec) = explode(" ", microtime());
        $unm = date('YmdHis', $sec) . (int)($usec * 10000);

        $data = array(
            'body' => "会员",
            'subject' => '大乐透',
            'out_trade_no' => $unm,
            'total_amount' => 9,
            'product_code' => 'QUICK_WAP_PAY'
        );
        $this->load->library('Alipay', $data);
        $a = " appid=wx565e2bee03941892&noncestr=e904831f48e729f9ad8355a894334700&package=Sign=WXPay&partnerid=1426998302&prepayid=wx20161228111411798751d5170900620997&timestamp=1482894853&key=uKWzXroxPObHjuoaJAXGTj1SlaE6HmgW";

    }


    public function weChatPay()
    {
        $Sign = self::MakeSign();
        $second = self::getMillisecond();
        $appid = "wx565e2bee03941892";
        $noncestr = "e904831f48e729f9ad8355a894334700";//随机字符串
        $package = "Sign=WXPay";
        $partnerid = "1426998302";
        $prepayid = "wx20161228111411798751d5170900620997";
        $timestamp = "1482894853";
        $key = "uKWzXroxPObHjuoaJAXGTj1SlaE6HmgW";

        $pay = "appid=" . $this->appId . "&noncestr=" . $Sign . "&partnerid=" . time() . "&timetamp=" . $second . "&key=" . $this->appKey;
        echo $pay;

    }

    public function pay_callback2()
    {

    }

    /*
     * 芝麻
     */
    public function zmCredit()
    {
        @file_put_contents('./log.txt', json_encode($_REQUEST, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    }


    public function payCallBack()
    {

        $msg = array();

        $postStr = file_get_contents('php://input');
        $msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (!empty($msg['nonce_str'])) {
            $data = array(
                'userid' => $msg['nonce_str'],
            );
            $row = $this->alipay_model->queryOrder($data);
            if ($row['uptime'] == $msg['transaction_id']) {
                echo '失败';
            } else {
                $this->Common_Models->updateData(array('nickname' => $row['nickname']), 'user', array('uptime' => $msg['transaction_id']));

                $num = self::Grade($msg['total_fee']);
                $this->alipay_model->OpenVip($row['nickname'], $num);
            }

        }
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }


    private static function MakeSign()
    {
        $str = "abcdefghijklnmobjistABCDEFGHIJKLNMOPQRSTXWY1234567890";
        $sign = substr(str_shuffle($str), 0, 10); //打乱字符串
        return md5($sign);
    }

    private static function Grade($Grade)
    {
        switch ($Grade) {
            case $Grade == "1000";
                $num = 1;
                return $num;
                break;
            case $Grade == "5000";
                $num = 2;
                return $num;
                break;
            case $Grade == "10000";
                $num = 3;
                return $num;
                break;
            default;
                break;
        }
    }

}