<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * alipy支付接口
 * @author onwulc@163.com
 *
 */
class Alipay extends CI_Controller
{
    private $product = [
        'vip_30' => 10.00,
        'vip_180' => 50.00,
        'vip_365' => 100.00,
    ];

    function __construct()
    {
        parent::__construct();
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


    }


    public function pay_callback()
    {

    }

    public function pay_callback2()
    {

    }


}