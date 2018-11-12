<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('setting')?>">基础设置</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('guanzhu')?>">关注设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('shenhe')?>">审核设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('ad')?>">流量主</a></li>
	<li><a href="<?php  echo $this->createWebUrl('poster')?>">海报设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('message')?>">模板消息</a></li>
	<li><a href="<?php  echo $this->createWebUrl('signin')?>">签到设置</a></li>
</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">客服消息推送设置</label>
                <div class="col-sm-9" style="color: red">
                    【务必完成这3点配置，再去填写下方内容】<br>
                    1.进入小程序公众平台后台->设置->开发设置 找到消息推送<br>
                    2.设置参数在-这个后台的 系统管理->微信小程序->本模块的管理设置->底部有一个消息推送配置。分别复制下来，粘贴到小程序公众平台消息推送位置。<br>
                    3.本小程序和想要跳转的小程序必须同时关联同一个服务号。<br>
                    4.请到左侧菜单客服消息中自定义引导关注公众号的客服消息。
                    <br><br>
                    【关注公众号送步数唯一小程序路径，填写到微信公众号底部菜单栏】/hc_step/pages/index/index?attention=1
                </div>
            </div>
            <div class="form-group">
				<label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">关注送步数</label>
				<div class="col-sm-8">
					<div class="input-group">
					<input type="text" name="guanzhu_step" class="form-control" value="<?php  echo $info['guanzhu_step'];?>">
					<div class='input-group-addon'>步</div>
					</div>
					<div style="color: red !important;">例：2000</div>
				</div>
			</div>
            <!-- <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文标题</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_title' value='<?php  echo $info["kefu_title"];?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文图片</label>
                <div class="col-sm-9">
                    <?php  echo tpl_form_field_image('kefu_img',$info['kefu_img'])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文概述</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_gaishu' value='<?php  echo $info["kefu_gaishu"];?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文链接</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_url' value='<?php  echo $info["kefu_url"];?>'>
                </div>
            </div> -->
        </div>
		</div>
		<div class="form-group col-sm-12">
			<input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1" <?php  if($update_status == 1) { ?>disabled<?php  } ?>>
			<input type="hidden" name="act" value="add" />
		</div>
	</form>
</div>



<!-- <script>

	$(document).ready(function(){
		$(".nav-tabs li:first").addClass("active")
		$(".con").hide()
		$(".con:first").show()
		$(".nav-tabs li").click(function(){
			$(".nav-tabs li").removeClass("active")
			$(this).addClass("active")
			$(".con").hide()
			var href=$(this).find("a").attr("href")
			$(href).fadeIn()
			return false
		})
	})
	$('#form1').submit(function() {

	});

	function closeWindow(url) 
	{ 
		var iWidth=300;//弹出窗口的宽度; 
        var iHeight=150;//弹出窗口的高度; 
        var iTop = (window.screen.availHeight - 30 - iHeight) / 2; //获得窗口的垂直位置
        var iLeft = (window.screen.availWidth - 10 - iWidth) / 2; //获得窗口的水平位置
        myWindow = window.open(url,"myWindow",'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no'); 
		//myWindow.document.write("运行中，请稍后...");
		//window.opener=null; 
		window.setTimeout(function(){
			myWindow.close();
			location.href="<?php  echo $this->createWebUrl('setting')?>"
		}, 1000); 

	} 
</script> -->

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>