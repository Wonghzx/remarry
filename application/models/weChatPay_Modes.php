<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/weChatlib/WxPay.Api.php';
require_once APPPATH . 'libraries/weChatlib/WxPay.JsApiPay.php';

class weChatPay_Modes extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function pay($id)
    {

    }

}