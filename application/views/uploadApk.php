<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width,user-scalable=0,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta name="renderer" content="webkit"/>
    <meta name="applicable-device" content="pc,mobile">
    <title>welcome to forlove</title>
    <link href="<?php echo base_url('static/css/mui.min.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('static/css/mui.css'); ?>" rel="stylesheet"/>
    <script src="<?php echo base_url('static/js/mui.min.js'); ?>"></script>
    <script src="http://libs.baidu.com/jquery/1.9.1/jquery.js"></script>
    <style type="text/css">
        *{margin:0; padding:0;}
        a{text-decoration: none;}
        img{max-width: 100%; height: auto;}
        .weixin-tip{display: none; position: fixed; left:0; top:0; bottom:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80);  height: 100%; width: 100%; z-index: 100;}
        .weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}
    </style>
</head>
<body>
<div class="weixin-tip">
    <p>
        <img src="<?php echo base_url('static/img/live_weixin.png');?>" alt="微信打开"/>
    </p>
</div>
<script type="text/javascript">
    $(window).on("load",function(){
        var winHeight = $(window).height();
        function is_weixin() {
            var ua = navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }
        var isWeixin = is_weixin();
        var url = "http://v6.rabbitpre.com/m/3imu2iZ?lc=1&sui=jiav2yfB&from=timeline&isappinstalled=0#from=share";
        if(isWeixin){
            $(".weixin-tip").css("height",winHeight);
            $(".weixin-tip").show();
        } else {
            location.href = "http://v6.rabbitpre.com/m/3imu2iZ?lc=1&sui=jiav2yfB&from=timeline&isappinstalled=0#from=share";
        }
    })
</script>
<script !src="">

</script>
</html>