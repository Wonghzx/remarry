<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/weChatlib/WxPay.Api.php';
require_once APPPATH . 'libraries/weChatlib/WxPay.JsApiPay.php';

/**
 * weChatPay微信支付接口
 * @author 842687571@qq.com
 */
class weChatPay
{

    private $openId;

    public $ci;

    private $mchId;

    function __construct()
    {

    }

    public function index($transaction_id)
    {

    }

    private function unifiedOrder()
    {

    }

}