<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <LINK rel="Bookmark" href="/favicon.ico">
    <LINK rel="Shortcut Icon" href="/favicon.ico"/>
    <!--[if lt IE 9]>
    <!--<script type="text/javascript" src="lib/html5.js"></script>-->
    <!--<script type="text/javascript" src="lib/respond.min.js"></script>-->
    <!--<script type="text/javascript" src="lib/PIE_IE678.js"></script>-->
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/static/h-ui/css/H-ui.min.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/static/h-ui.admin/css/H-ui.admin.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/lib/Hui-iconfont/1.0.7/iconfont.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/lib/icheck/icheck.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/static/h-ui.admin/skin/default/skin.css'); ?>" id="skin"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/admin/static/h-ui.admin/css/style.css'); ?>"/>

    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>Welcome to ForLove</title>
</head>
<body>
<?php $this->load->view('admin/header.php'); ?>
<aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value=""/>
    <div class="menu_dropdown bk_2">
        <dl id="menu-member">
            <dt><i class="Hui-iconfont">&#xe60d;</i>用户管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="<?php echo base_url('Admin/Member/MemberList'); ?>" data-title="用户管理" href="javascript:void(0)">用户管理</a></li>
                </ul>
            </dd>
        </dl>
        <dl id="menu-article">
            <dt><i class="Hui-iconfont">&#xe616;</i> 审核管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="<?php echo base_url('Admin/Authentication/identity'); ?>" data-title="身份审核" href="javascript:void(0)">身份审核</a></li>
                </ul>
            </dd>
    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
</div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active"><span title="我的桌面" data-href="<?php echo base_url('Admin/Index/Welcome'); ?>">我的桌面</span><em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
          <a id="js-tabNav-prev" class="btn radius btn-default size-S"href="javascript:;">
              <i class="Hui-iconfont">&#xe6d4;</i>
          </a>
          <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;">
              <i class="Hui-iconfont">&#xe6d7;</i>
          </a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="<?php echo base_url('Admin/Index/Welcome'); ?>"></iframe>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/jquery/1.9.1/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/layer/2.1/layer.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui/js/H-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui.admin/js/H-ui.admin.js'); ?>"></script>

<script type="text/javascript">
    /*资讯-添加*/
    function article_add(title, url) {
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }
    /*图片-添加*/
    function picture_add(title, url) {
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }
    /*产品-添加*/
    function product_add(title, url) {
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }
    /*用户-添加*/
    function member_add(title, url, w, h) {
        layer_show(title, url, w, h);
    }
</script>
<script type="text/javascript">
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?080836300300be57b7f34f4b3e97d911";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s)
    })();
    var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F080836300300be57b7f34f4b3e97d911' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>
