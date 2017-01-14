<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/Alipaylib/alipay_submit.class.php';

class PayVip
{

    protected $params;

    /*
     * $params array
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function abc()
    {

    }
}
