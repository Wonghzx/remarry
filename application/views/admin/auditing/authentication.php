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
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>审核管理</title>
    <style>
    .box {
            display: none;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background: #111111;
            position: absolute;
            opacity: 0.4;
            float: left;
            z-index:1001;
            -moz-opacity: 0.8;
      }
    .mian {
          display: none;
          position: absolute;
          top: 18%;
          left: 35%;
          width: 40%;
          height: 60%;
          padding: 16px;
          z-index:1002;
          border-radius: 2px;
      }
    </style>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;
     </i> 首页
      <span class="c-gray en">&gt;审核管理</span>
      <span class="c-gray en">&gt;</span> 审核认证
      <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"href="javascript:location.replace(location.href);" title="刷新">
        <i class="Hui-iconfont">&#xe68f;</i>
      </a>
</nav>
<div class="page-container">
  <div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="r">共有数据：<?php echo $countInfo;?><strong></strong> 条</span>
</div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-hover table-sort">
            <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="80">ID</th>
                <th width="80">用户名</th>
                <th width="180">图片</th>
                <th width="120">更新时间</th>
                <th width="60">状态</th>
                <th width="60">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($dataInfo)) {?>
            <?php foreach ($dataInfo as $item => $value){ ?>
            <tr class="text-c">
                <td><input type="checkbox" value="" name=""></td>
                <td><?php echo $countInfo++;?></td>
                <td class="text-l"><p style="text-align:center ;"><?php echo $value['nickname']; ?></p></td>
                <td>
                    <?php foreach ($value['photo'] as &$val) {?>
                         <img src="<?php echo $val;?>" alt="" style="cursor:pointer;" width="45px" height="40px" onclick="showImg('<?php echo $val;?>');">
                    <?php }?>
                </td>
                <td><?php echo date('Y-m-d H:i:s',$value['add_time']);?></td>
                <td class="td-status">
                  <?php if($value['autstate'] == "1"){?>
                  <span class="label label-success radius">通过</span>
                  <?php }  else if ($value['autstate'] == "3") { ?>
                   <span class="label label-danger radius">未通过</span>
                  <?php } else {?>
                    <span class="label label-success radius">等待</span>
                  <?php }?>
                </td>
                <td class="f-14 td-manage">
                  <?php if ($value['autstate'] == "2") {?>
                  <a style="text-decoration:none" onClick="article_shenhe(this,'<?php echo $value['id'];?>','<?php echo $value['nickname'];?>','user')" href="javascript:;" title="审核">
                     <i class="">审核</i>
                  </a>
                  <?php }?>
                  <a style="text-decoration:none" class="ml-5" onClick="picture_del(this,'<?php echo $value['id'];?>','authentication')" href="javascript:;" title="删除">
                    <i class="Hui-iconfont">&#xe6e2;</i>
                  </a>
              </td>
            </tr>
          <?php }?>
          <?php } else {?>
            <tr>
              <td colspan="10">亲，查不到相关记录哦！</td>
            </tr>
          <?php }?>
            <div class="box"></div>
            <div class="mian"></div>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/jquery/1.9.1/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/layer/2.1/layer.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/lib/My97DatePicker/WdatePicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui/js/H-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/static/h-ui.admin/js/H-ui.admin.js'); ?>"></script>
<script type="text/javascript">
   $('.table-sort').dataTable({
       "aaSorting": [[1, "desc"]],//默认第几个排序
       "bStateSave": true,//状态保存
       "aoColumnDefs": [
           //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
           {"orderable": false, "aTargets": [0, 8]}// 不参与排序的列
       ]
   });

var setAjax = {
   showAjax:function(nickname,state,table) {
        $.ajax({
           type  :  'post',
           url   :  "<?php echo base_url('admin/Authentication/setState');?>",
           data  :   {"nickname":nickname + state,"table":table},
           dateType : 'json',
           contentType: "application/x-www-form-urlencoded; charset=utf-8",
           success : function (data) {

           }
        });
    }
};
    /*资讯-删除*/
    function picture_del(obj,id,table){
    	layer.confirm('确认要删除吗？',function(index){
    		$(obj).parents("tr").remove();
    		layer.msg('已删除!',{icon:1,time:1000});
        $.ajax({
           type  :  'post',
           url   :  "<?php echo base_url('admin/Authentication/delAuthentication');?>",
           data  :   {"id":id,"table":table},
           dateType : 'json',
           success : function (data) {

           }
        });
    	});
    }
    /*资讯-审核*/
    function article_shenhe(obj, id,nickname,table) {
        layer.confirm('审核身份？', {
                btn: ['通过', '不通过', '取消'],
                shade: false,
                closeBtn: 0
            },
            function () {
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">通过</span>');
                $(obj).remove();
                layer.msg('通过', {icon: 6, time: 1000});
               var ajax = setAjax.showAjax(nickname,200,table);

            },
            function () {
                $(obj).parents("tr").find(".td-status").html('<span class="label label-danger radius">未通过</span>');
                $(obj).remove();
                layer.msg('未通过', {icon: 5, time: 1000});
                var ajax = setAjax.showAjax(nickname,400,table);
            });
    }


   function showImg (imgUrl) {
     $('.box').css('display','block');//.slideDown(1000)
     $('.mian').prepend("<img src="+imgUrl+" alt='' class='nones_hows' width='100%' height='100%' onClick='showOut();'>").toggle('slow').css('display','block');
   }
   function showOut() {
     $('.nones_hows').remove();
     $('.box').css('display','none');
     $('.mian').css('display','none');
   }


</script>
</body>
</html>
