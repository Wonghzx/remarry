<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * alipy支付接口
 * @author onwulc@163.com
 *
 */
require_once APPPATH . "models/alipay/alipay_notify.class.php";
require_once APPPATH . "models/alipay/alipay_submit.class.php";

class Alipay extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
//        $this->load->model('order_model');
    }

    public function index()
    {

        $input_charset = $this->config->item('alipay_config');



//        $alipayNotify = new AlipayNotify($input_charset);
//        $verify_result = $alipayNotify->verifyNotify();


//        if ($verify_result) {
//            $out_trade_no = $this->input->post('out_trade_no', TRUE);
//            //支付宝交易号
//            $trade_no = $this->input->post('trade_no', TRUE);
//            //交易状态
//            $trade_status = $this->input->post('trade_status', TRUE);
//            $this->log_result('alipay_notify', "【支付宝回调App】:\n" . json_encode($_POST) . "\n");
//            if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
////                //判断该笔订单是否在商户网站中已经做过处理
////                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
////                //如果有做过处理，不执行商户的业务程序
////
//                $order = $this->order_model->get_order_info($out_trade_no);
////
//                if ($order['TradeStatus'] != 'TRADE_FINISHED' && $order['TradeStatus'] != 'TRADE_SUCCESS') {
//                    $data = array('TradeStatus' => $trade_status, 'TradeNo' => $trade_no, 'PayTime' => time(), 'PayType' => 'alipay');
//                    $this->order_model->update_order_info($out_trade_no, $data);
//                }
//            }
//            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
//            echo "success";        //请不要修改或删除
//        } else {
//            //验证失败
//            echo "error";
//
//            //调试用，写文本函数记录程序运行情况是否正常
//            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
//        }

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $this->input->post('WIDout_trade_no', TRUE);

        //订单名称，必填
        $subject = $this->input->post('WIDsubject', TRUE);

        //付款金额，必填
        $total_fee = $this->input->post('WIDtotal_fee', TRUE);

        //收银台页面上，商品展示的超链接，必填
        $show_url = $this->input->post('WIDshow_url', TRUE);

        //商品描述，可空
        $body = $this->input->post('WIDbody', TRUE);

        $parameter = array(
            "service" => 'alipay.wap.create.direct.pay.by.user',
            "partner" => $input_charset['partner'],
            "seller_id" => $input_charset['partner'],
            "payment_type" => 1,
            "notify_url" => $input_charset['notify_url'],
            "return_url" => $input_charset['return_url'],
            "_input_charset" => trim(strtolower($input_charset['input_charset'])),
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "show_url" => $show_url,
            //"app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
            "body" => $body,
            //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
            //如"参数名"	=> "参数值"   注：上一个参数末尾需要“,”逗号。
        );

        $alipaySubmit = new AlipaySubmit($parameter);
//        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
//        echo($html_text);

        $this->load->view('ceshi');
    }

    function log_result($log_type, $word)
    {
        $data = array(
            'LogType' => $log_type,
            'CreatedAt' => time(),
            'Log' => strftime("%Y-%m-%d-%H：%M：%S", time()) . "\n" . $word . "\n\n"
        );
        //$this->db->insert('sys_logs', $data);
    }
}