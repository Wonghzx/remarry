<?php
/* *
 * 配置文件
 * 版本：1.0
 * 日期：2016-06-06
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
$config['app_id']		= '2016111102723173';

//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
$config['private_key']	= 'MIICXQIBAAKBgQD93Ydck5lbHmRA89npV6/eEvah0hIISoA+ubi5BLoBGy1n/aXK
akM9Kz0M1wn95Z0rWbqzMzeqkGiqL7kcYRQ4n8eMvr9pQTEB5hdI61s8j1iICxxb
Ke8mQTr6ITYnnxn6Z1o02yQIiEJSUtkWNpzdpxzIUpwMGqZtor+tdZq9owIDAQAB
AoGAVL1BldFe+19lr5i2QIGYntRNpy5r/oB/1nBADOJbBEuCGJg+YxadYymvsz2E
hE/E6teEzpRmHHUfus87RxHQoXvi0exPgpjos0WvEUcJ/XqLou1dYg7iNv958zvY
h10o/FnvXf4WQESWl3Bpay+RcYYBtNSf03L1fuBNAGfgo8ECQQD/mENZLq0E6c1k
DX7rCHeGFqY2W23vP4FcHRDxw810zx89ZXUVPZtC4cWLjcAShKnkKDxasblKli4n
kcyo5yE7AkEA/kSQUq84jlfR/kod/9EmEMfVRkM0JGbVapsYETXuQAIAz6oK1o+1
XvLIJPMbDl4fW+/hIlEdptA8+rBrxB+OuQJANVAnTCTmRg4Wn3YJMTE07S0wQkpT
5gGTFAmkDSnQYzsIwx+0iletWQgK0o8grzwoy8RwwmIryhkFS4+n5ts/HQJBAOCm
VVkohPCGuPtSIsU11cr3tAB7OeN7k823sADsxE57NppDo4XEvLtiB+FVhS8hi9Vf
0GrQfkE3NNlM7DoAo8kCQQD8oLlSbrVMHnasvsYGQ6QbkgtlFYCxMANDWGkX298l
kM6yy8Cb7EXvzA7q4eqz4ttO51kVYt6bj0x7Ypao9Hwm';

//支付宝的公钥，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
$config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';

//异步通知接口
//$config['service']= 'mobile.securitypay.pay';

// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['notify_url'] = "http://119.29.143.48/remarry/Test/index/alipay.wap.create.direct.pay.by.user-PHPUTF-8/notify_url.php";

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['return_url'] = "http://119.29.143.48/remarry/Test/index/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/return_url.php";
//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//签名方式 不需修改
$config['sign_type']    = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
$config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$config['cacert']    = APPPATH.'models/alipay/cacert.pem';  //getcwd()

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$config['transport']    = 'http';


?>