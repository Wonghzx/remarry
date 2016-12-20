<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once './aop/AopClient.php';

/**
 * Class MY_Alipay
 */
class Alipay
{
    protected $alipay = null;
    public $ci = null;

    /**
     * MY_Alipay constructor.
     */
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->alipay = new AopClient;
        $this->alipay->appId = $this->ci->config->item('app_id');
        $this->alipay->rsaPrivateKey = $this->ci->config->item('private_key');
        $this->alipay->alipayrsaPublicKey = $this->ci->config->item('public_key');
    }
    /**
     * @param array $content 业务请求参数
     */
    public function payOrder(array $content)
    {
       require_once './aop/AlipayTradeAppPayRequest.php';

        $request = new AlipayTradeAppPayRequest();
        $request->setBizContent(json_encode($content));
//        $request->setNotifyUrl();
//        $request->setReturnUrl();
//        $request->setNeedEncrypt();
        return  $this->alipay->execute($request);
    }

    public function cancelOrder()
    {

    }

    public function queryOrder()
    {

    }




}