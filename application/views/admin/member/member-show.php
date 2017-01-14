<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <!--[if lt IE 9]>
    <!--<script type="text/javascript" src="lib/html5.js"></script>-->
    <!--<script type="text/javascript" src="lib/respond.min.js"></script>-->
    <!--<script type="text/javascript" src="lib/PIE_IE678.js"></script>-->
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/static/h-ui/css/H-ui.min.css'); ?>"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('static/admin/static/h-ui.admin/css/H-ui.admin.css'); ?>"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('static/admin/lib/Hui-iconfont/1.0.7/iconfont.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/lib/icheck/icheck.css'); ?>"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('static/admin/static/h-ui.admin/skin/default/skin.css'); ?>" id="skin"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('static/admin/static/h-ui.admin/css/style.css'); ?>"/>
    <!--[if IE 6]>

    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]--><title>用户查看</title>
</head>
<body>
<div class="cl pd-20" style=" background-color:#5bacb6">
    <img class="avatar size-XL l" src="<?php echo $userInfo['photo']?>">
    <dl style="margin-left:80px; color:#fff">
        <dt><span class="f-18"><?php echo $userInfo['nickname'];?></span> <span class="pl-10 f-12">学历：<?php echo $userInfo['education'];?></span></dt>
        <dd class="pt-10 f-12" style="margin-left:0">签名：<?php echo $userInfo['monologue'];?></dd>
    </dl>
</div>
<div class="pd-20">
    <table class="table">
        <tbody>
        <tr>
            <th class="text-r" width="80">年龄：</th>
            <td><?php echo $userInfo['age']; ?></td>
        </tr>
        <tr>
            <th class="text-r">手机：</th>
            <td><?php echo $userInfo['username']; ?></td>
        </tr>
        <tr>
            <th class="text-r">身高：</th>
            <td><?php echo $userInfo['height']; ?></td>
        </tr>
        <tr>
            <th class="text-r">体型：</th>
            <td><?php echo $userInfo['weight']; ?></td>
        </tr>
        <tr>
            <th class="text-r">普通积分：</th>
            <td>
                <?php if (!empty($userInfo['integral'])) { ?>
                    <?php echo $userInfo['integral']; ?>
                <?php } else { ?>
                    0
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th class="text-r">会员积分：</th>
            <td>
                <?php if (!empty($userInfo['memberintegral'])) { ?>
                    <?php echo $userInfo['memberintegral']; ?>
                <?php } else { ?>
                    0
                <?php } ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<!--<script type="text/javascript" src="js/jquery.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui/js/H-ui.js'); ?>"></script>
<script type="text/javascript"
        src="<?php echo base_url('static/admin/static/h-ui.admin/js/H-ui.admin.js'); ?>"></script>
</body>
</html>