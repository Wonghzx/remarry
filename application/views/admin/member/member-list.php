<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
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
    <title>用户管理</title>
    <style>
        .dataTables_info {
            display: none;
        }

        #DataTables_Table_0_paginate {
            display: none;
        }

        .page {
            margin-top: 1%;
            float: left;
            width: 100%;

        }

        .page ul {

            width: 41%;
            margin: 0 auto;
            height: 5%;

        }

        .page ul li {
            margin: 0 auto;
        }

        .page a,
        .page a:visited {
            float: left;
            background: #fff;
            margin: 0 0 10px 5px;
            padding: 8px 11px;
            line-height: 100%;
            border: 1px solid #ebebeb;
            border-radius: 2px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.03);

        }

        .page .current,
        .page .dots {
            background: #fff;
            float: left;
            margin: 0 0 0 5px;
            padding: 8px 11px;
            line-height: 100%;
            border: 1px solid #ebebeb;
            border-radius: 2px;
        }


       #cla {
            background: rgb(192, 30, 34) none repeat scroll 0% 0%;
            border-radius: 3px;
            color: rgb(255, 255, 255);
            box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgb(192, 30, 34);
        }

        .page a:hover, .page span.current {
            background: #C01E22;
            color: #fff;
            border: 1px solid #C01E22;

        }
    </style>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户中心 <span
        class="c-gray en">&gt;</span> 用户管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
                                              href="javascript:location.replace(location.href);" title="刷新"><i
            class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <!--    <div class="text-c">-->
    <!--        <input type="text" class="input-text" style="width:250px" placeholder="输入会员名称、电话、邮箱" id="" name="">-->
    <!--        <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户-->
    <!--        </button>-->
    <!--    </div>-->
    <div class="cl pd-5 bg-1 bk-gray mt-20">
<!--        <span class="l"><a href="javascript:;" onclick="datadel()"-->
<!--                                                               class="btn btn-danger radius"><i class="Hui-iconfont">-->
<!--                    &#xe6e2;</i> 批量删除</a> <a href="javascript:;" onclick="member_add('添加用户','member-add.html','','510')"-->
<!--                                             class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>-->
<!--                添加用户</a></span> -->
        <span class="r">共有数据：<strong><?php echo $countUserInfo; ?></strong> 条</span></div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="80">ID</th>
                <th width="100">用户名</th>
                <th width="40">性别</th>
                <th width="90">手机</th>
                <th width="150">微信</th>
                <th width="">地址</th>
                <th width="130">加入时间</th>
                <th width="70">状态</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($userInfo as $item => $value) { ?>
                <tr class="text-c">
                    <td><input type="checkbox" value="1" name=""></td>
                    <td><?php echo $i++; ?></td>
                    <td><u style="cursor:pointer" class="text-primary"
                           onclick="member_show('<?php echo $value['nickname']; ?>','<?php echo base_url('Admin/Member/visitingCardUser?nickname=') . $value['nickname']; ?>','10001','360','400')"><?php echo $value['nickname']; ?></u>
                    </td>
                    <td><?php echo $value['sex']; ?></td>
                    <td><?php echo $value['username']; ?></td>
                    <td><?php echo $value['wechat']; ?></td>
                    <td class="text-l"><?php echo $value['nowlocal']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', $value['add_time']); ?></td>
                    <td class="td-status" id="<?php echo $value['latitude'] ?>">
                        <?php if ($value['status'] == "1") { ?>
                            <span class="label label-success radius">在线</span>
                        <?php } else { ?>
                            <span class="label label-defaunt radius">已下线</span>
                        <?php } ?>
                    </td>
                    <td class="td-manage">
                        <a style="text-decoration:none"
                           onClick="member_status(<?php echo $value['userid'] ?>,'<?php echo $value['latitude'] ?>')"
                           href="javascript:;"
                           title="刷新在线">
                            <i class="Hui-iconfont">&#xe631;</i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php echo $pageInfo; ?>
</div>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/jquery/1.9.1/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/layer/2.1/layer.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/laypage/1.2/laypage.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/My97DatePicker/WdatePicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui/js/H-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui.admin/js/H-ui.admin.js'); ?>"></script>

<script type="text/javascript">
    $(function () {
        $('.table-sort').dataTable({
            "aaSorting": [[1, "desc"]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                {"orderable": false, "aTargets": [0, 8, 9]}// 制定列不参与排序
            ]
        });
        $('.table-sort tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
    });
    /*用户-添加*/
    function member_add(title, url, w, h) {
        layer_show(title, url, w, h);
    }
    /*用户-查看*/
    function member_show(title, url, id, w, h) {
        layer_show(title, url, w, h);
    }
    /*用户-刷新用户在线*/
    function member_status(obj, id) {
//        layer.confirm('确认要停用吗？', function (index) {
//            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="member_start(this,id)" href="javascript:;" title="启用"><i class="Hui-iconfont">&#xe6e1;</i></a>');
//            $(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">已停用</span>');
//            $(obj).remove();
//            layer.msg('已停用!', {icon: 5, time: 1000});
//        });
        $.ajax({
            type: 'post',
            url: '<?php echo base_url('Admin/Member/queryStatus');?>',
            data: {'obj': obj},
            dataType: "json",
            success: function (data) {
                if (data.status == "0") {
                    $('#' + id).html('<span class="label label-defaunt radius">已下线</span>');
                    $(this).remove();
                } else {
                    $('#' + id).html('<span class="label label-success radius">在线</span>');
                    $(this).remove();
                }
            }
        });
    }

    /*用户-启用*/
    function member_start(obj, id) {
        layer.confirm('确认要启用吗？', function (index) {
            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="member_status(this,id)" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
            $(obj).remove();
            layer.msg('已启用!', {icon: 6, time: 1000});
        });
    }
    /*用户-编辑*/
    function member_edit(title, url, id, w, h) {
        layer_show(title, url, w, h);
    }
    /*密码-修改*/
    function change_password(title, url, id, w, h) {
        layer_show(title, url, w, h);
    }
    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            $(obj).parents("tr").remove();
            layer.msg('已删除!', {icon: 1, time: 1000});
        });
    }
</script>
</body>
</html>
