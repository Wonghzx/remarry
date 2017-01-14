<?php
/**
 * TODO: 修改这里配置为您自己申请的商户信息
 * 微信公众号信息配置
 *
 * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
 *
 * MCHID：商户号（必须配置，开户邮件中可查看）
 *
 * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
 * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
 *
 * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
 * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
 * @var string
 */

$config['appId'] = 'wx565e2bee03941892';

$config['mchId'] = '1945986258';

$config['key'] = 'uKWzXroxPObHjuoaJAXGTj1SlaE6HmgW';

$config['appSecret'] = 'd19a022c84ad98265f3e53ff8172f80d';

$config['sslcertPath'] =  APPPATH.'libraries/weChatlib/cert/apiclient_cert.pem';

$config['sslkeyPath'] = APPPATH.'libraries/weChatlib/cert/apiclient_key.pem';

?>