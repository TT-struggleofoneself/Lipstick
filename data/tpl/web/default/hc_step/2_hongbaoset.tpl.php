<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">

    <li><a href="<?php  echo $this->createWebUrl('hongbao')?>">步数币礼包列表</a></li>

    <li><a href="<?php  echo $this->createWebUrl('hongbao_post')?>">添加步数币礼包</a></li>

    <li class="active"><a href="<?php  echo $this->createWebUrl('hongbaoset')?>">基础设置</a></li>

</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数币礼包背景图</label>
                    <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('hongbaobg',$info['hongbaobg']);?>
                    <div style="color: red !important;">建议尺寸：270*320。此处为步数币礼包分享完成后的背景图
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页功能说明文字</label>
                    <div class="col-sm-8">
                    <input type="text" name="hongbaotext" class="form-control" value="<?php  echo $info['hongbaotext'];?>">
                    <div style="color: red !important;">例：福利活动，邀请新用户助力</div>
                    </div>
                </div>            
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