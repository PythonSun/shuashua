<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:38:"./views/admin/pc/auth_group/post.phtml";i:1595431675;s:84:"/www/wwwroot/appoint.toutiaozhannei.cn/web/public/views/admin/pc/common/header.phtml";i:1595431654;s:85:"/www/wwwroot/appoint.toutiaozhannei.cn/web/public/views/admin/pc/common/sidebar.phtml";i:1595431656;s:85:"/www/wwwroot/appoint.toutiaozhannei.cn/web/public/views/admin/pc/auth_group/nav.phtml";i:1595431674;s:86:"/www/wwwroot/appoint.toutiaozhannei.cn/web/public/views/admin/pc/auth_group/tabs.phtml";i:1595431677;s:84:"/www/wwwroot/appoint.toutiaozhannei.cn/web/public/views/admin/pc/common/footer.phtml";i:1595431653;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理系统 | 管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- 弹出框 -->
    <link rel="stylesheet" type="text/css" href="/static/plugins/SmallPop/spop.min.css">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/plugins/Ionicons/css/ionicons.min.css">
    <!-- 图片展示插件样式 -->
    <link rel="stylesheet" href="/static/plugins/magnify/dist/jquery.magnify.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/plugins/AdminLTE/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/static/plugins/AdminLTE/css/skins/_all-skins.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/static/plugins/html5shiv.min.js"></script>
    <script src="/static/plugins/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/static/plugins/SmallPop/spop.min.js"></script>
    <!-- jQuery 3 -->
    <script src="/static/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- 剪切板 -->
    <script type="text/javascript" src="/static/plugins/clipboard.min.js"></script>
    <!-- 图片展示插件 -->
    <script type="text/javascript" src="/static/plugins/magnify/dist/jquery.magnify.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/static/plugins/AdminLTE/js/adminlte.min.js"></script>
    <!--引入JS-->
    <script src="/static/admin/web/js/global.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/admin" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>系统</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>管理系统</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img onerror="this.src='/static/admin/web/images/avatar.png'" src="<?php echo to_media($admin['avatar']); ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $admin['username']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img onerror="this.src='/static/admin/web/images/avatar.png'" src="<?php echo to_media($admin['avatar']); ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo $admin['username']; ?>
                                    <small><?php echo date('Y年m月d日'); ?></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo U('index/profile'); ?>" class="btn btn-default btn-flat">资料设置</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo U('auth/login'); ?>" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img onerror="this.src='/static/admin/web/images/avatar.png'" src="<?php echo to_media($admin['avatar']); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $admin['username']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i>在线</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">功能菜单</li>

            <?php if(is_array($treeMenu) || $treeMenu instanceof \think\Collection || $treeMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $treeMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$oo): $mod = ($i % 2 );++$i;if($oo['childnum'] == '0'): ?>
                <li <?php if(menuActive($oo['name'], $oo['level'])): ?>class="active"<?php endif; ?>><a href="<?php echo U($oo['name']); ?>"><i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span></a></li>
                <?php elseif($oo['level'] == '1'): ?>
                <li class="treeview <?php if(menuActive($oo['name'], $oo['level'])): ?>active<?php endif; ?>">
                    <a href="javascript:void(0);">
                        <i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                        <?php if(is_array($oo['children']) || $oo['children'] instanceof \think\Collection || $oo['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $oo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$to): $mod = ($i % 2 );++$i;?>
                        <li <?php if(menuActive($to['name'])): ?>class="active"<?php endif; ?>><a href="<?php echo U($to['name']); ?>"><i class="<?php echo $to['icon']; ?>"></i><?php echo $to['title']; ?></a></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </li>
                <?php endif; endforeach; endif; else: echo "" ;endif; if($_SERVER['SERVER_NAME'] == 'tuiguang.51muma.com'): ?>
            <li <?php if(in_array($controller,['lock'])): ?>class="active"<?php endif; ?>>
            <a href="<?php echo U('lock/index'); ?>">
                <i class="fa fa-dashboard"></i>
                <span>授权管理</span>
            </a>
            </li>
            <?php endif; ?>

            <li class="header">其他功能</li>
            <li><a href="http://%77%77%77%2E%73%6F%75%68%6F%2E%6E%65%74" target="_blank">
                    <i class="fa fa-circle-o text-red"></i>
                    <span>帮助手册</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-circle-o text-yellow"></i>
                    <span>网站概况</span>
                </a>
            </li>
            <li>
                <a href="http://%77%77%77%2E%73%6F%75%68%6F%2E%6E%65%74" target="_blank">
                    <i class="fa fa-circle-o text-aqua"></i>
                    <span>技术支持</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<style type="text/css">
.rule_node p{ clear:both; margin-bottom:0px; padding: 6px;}
.rule_node p .checkbox-inline{ padding-top:0px;}
.rule_node .left1{ background:#f9f9f9;}
.rule_node .left2{ float:left; margin-left:24px;}
.rule_node .left3{ margin-left:0px; clear:none;}
.rule_node .p_left{ float:left; }
</style>

<div class="content-wrapper">
    <section class="content-header">
    <h1>
        角色管理
        <small>
            <?php if($action == 'index'): ?>
            角色列表
            <?php else: if(empty($item)): ?>添加角色<?php else: ?>修改角色<?php endif; endif; ?>
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>角色管理</a></li>
        <li class="active">
            <?php if($action == 'index'): ?>
            角色列表
            <?php else: if(empty($item)): ?>添加角色<?php else: ?>修改角色<?php endif; endif; ?>
        </li>
    </ol>
</section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
    <li <?php if(in_array($action,['index'])): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('index'); ?>" >角色列表</a>
    </li>
    <?php if(in_array($action,['post']) && !empty($item)): ?>
        <li class="active">
            <a href="<?php echo U('post', 'id=' . $item['id']); ?>">修改角色</a>
        </li>
    <?php endif; ?>
    <li <?php if(in_array($action,['post']) && empty($item)): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('post'); ?>">添加角色</a>
    </li>
</ul>
                    <div class="tab-content">
                        <form method="post" class="form-horizontal form" id="post_form">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    角色信息
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色名称</label>
                                        <div class="col-sm-7"><input class="form-control" name="title" value="<?php echo !empty($item['title'])?$item['title']:''; ?>" placeholder="请输入角色名称"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色描述</label>
                                        <div class="col-sm-7"><input class="form-control" name="notation" value="<?php echo !empty($item['notation'])?$item['notation']:''; ?>" placeholder="请输入角色描述"></div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label">角色图标</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input class="form-control" name="pic" value="<?php echo !empty($item['pic'])?$item['pic']:''; ?>" placeholder="角色图标" >
                                                <span class="input-group-btn">
                                                    <a href="<?php echo (isset($item['pic']) && ($item['pic'] !== '')?$item['pic']:'/static/global/face/no-image.png'); ?>" target="_blank" >
                                                        <img src="<?php echo (isset($item['pic']) && ($item['pic'] !== '')?$item['pic']:'/static/global/face/no-image.png'); ?>" style="height:34px; width:68px;" />
                                                    </a>
                                                    <button class="btn btn-success btn-flat up-btn" type="button" data-url="<?php echo url('Uploads/upload'); ?>?dir=image">
                                                        <i class="fa fa-cloud-upload"> 上传</i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">推荐显示</label>
                                        <div class="col-sm-7">
                                            <select class="form-control select2" name="recom" style="width:100%;">
                                                <option value="0" <?php echo !empty($item['recom'])?'selected':''; ?>>是</option>
                                                <option value="1" <?php echo !empty($item['recom'])?'':'selected'; ?>>否</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">所属模块</label>
                                        <div class="col-sm-7">
                                            <select class="form-control select2" name="module" style="width:100%;">
                                                <option value="admin" <?php echo !empty($item['module'])?'selected':''; ?>>后台管理员</option>
                                                <option value="member" <?php echo !empty($item['module'])?'':'selected'; ?>>前台会员</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">状态</label>
                                        <div class="col-sm-7">
                                            <select class="form-control select2" name="status" style="width:100%;">
                                                <option value="1" <?php echo !empty($item['status'])?'selected':''; ?>>启用</option>
                                                <option value="0" <?php echo !empty($item['status'])?'':'selected'; ?>>禁用</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">角色授权</label>
                                        <div class="col-sm-7 rule_node">
                                        <?php if(is_array($authRuleTree) || $authRuleTree instanceof \think\Collection || $authRuleTree instanceof \think\Paginator): $i = 0; $__LIST__ = $authRuleTree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rule_list): $mod = ($i % 2 );++$i;?>
                                        <p class='left<?php echo $rule_list['level']; if($rule_list['level'] == 3): ?> p_left<?php endif; ?>' >
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="rules[]" value="<?php echo $rule_list['id']; ?>" class="minimal" <?php if(isset($rule_list['ischeck']) &&$rule_list['ischeck'] == 'y'): ?>checked="checked"<?php endif; ?> > <?php echo $rule_list['title']; ?>
                                        </label>
                                        </p>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-6 col-xs-12 col-sm-offset-2">
                                            <input type="hidden" name="id" value="<?php echo !empty($item['id'])?$item['id']:0; ?>">
                                            <button type="button" name="submit" class="btn btn-primary">提交</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script type="text/javascript">
                            $(function () {
                                //提交分类信息
                                $("button[name='submit']").click(function () {
                                    $.post(
                                        window.location.href,
                                        $('#post_form').serialize(),
                                        function (res) {
                                            message(res.message,res.redirect,res.type)
                                        },'json'
                                    );
                                });
                            });
                        </script>
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    </section>
</div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.0.0
                </div>
                <strong><SCRIPT LANGUAGE=Javascript>
var _4cad=["\x3c\x66\x6f\x6e\x74\x20\x63\x6f\x6c\x6f\x72\x3d\x62\x6c\x75\x65\x3e\u7a0b\u5e8f\u63d0\u4f9b\uff1a\x3c\x2f\x66\x6f\x6e\x74\x3e\x3c\x61\x20\x68\x72\x65\x66\x3d\'\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x73\x6f\x75\x68\x6f\x2e\x6e\x65\x74\'\x20\x74\x61\x72\x67\x65\x74\x3d\'\x5f\x62\x6c\x61\x6e\x6b\'\x3e\x3c\x75\x3e\x3c\x66\x6f\x6e\x74\x20\x63\x6f\x6c\x6f\x72\x3d\x72\x65\x64\x3e\u641c\u864e\u7cbe\u54c1\u793e\u533a\x3c\x2f\x66\x6f\x6e\x74\x3e\x3c\x2f\x75\x3e\x3c\x2f\x61\x3e"];window["\x64\x6f\x63\x75\x6d\x65\x6e\x74"]["\x77\x72\x69\x74\x65\x6c\x6e"](_4cad[0x0]);
</SCRIPT></strong>
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
    </body>
</html>

