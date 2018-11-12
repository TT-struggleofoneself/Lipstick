<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('setting')?>">基础设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('guanzhu')?>">关注设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('shenhe')?>">审核设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('ad')?>">流量主</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('poster')?>">海报设置</a></li>
    <li><a href="<?php  echo $this->createWebUrl('message')?>">模板消息</a></li>
    <li><a href="<?php  echo $this->createWebUrl('signin')?>">签到设置</a></li>
</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">“汗水日记”文字</label>
                <div class="col-sm-9">
                    <input class='form-control' name='sweattext' value='<?php  echo $info["sweattext"];?>'>
                    <div style="color: red !important;">例：汗水日记</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">海报图激励文字</label>
                <div class="col-sm-9">
                    <input class='form-control' name='comeon' value='<?php  echo $info["comeon"];?>'>
                    <div style="color: red !important;">例：坚持，就是我对自己的承诺。不要超过15个字</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">“汗水日记”小图标</label>
                <div class="col-sm-9">
                    <?php  echo tpl_form_field_image('icon',$info['icon'])?>
                    <div style="color: red !important;">建议比例：30*30</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">海报页面背景图</label>
                <div class="col-sm-9">
                    <?php  echo tpl_form_field_image('posterpic',$info['posterpic'])?>
                    <div style="color: red !important;">尺寸：688*772。海报必须按照标注尺寸，否则会错位变形</div>
                    <div class="input-group " style="margin-top:.5em;">
							<span style="color:red;">默认：</span>
                            <img class="img-responsive img-thumbnail" style="width:150px;" src="http://we10.66bbn.com/addons/hc_step/tu1.jpg"/>
		            </div>
                </div>
            </div>            
            <!-- <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">海报背景图</label>
                <div class="col-sm-9">
                    <?php  echo tpl_form_field_image('posterbg',$info['posterbg'])?>
                    <div style="color: red !important;">尺寸：688*893。海报必须按照标注尺寸，否则会错位变形</div>
                    <div class="input-group " style="margin-top:.5em;">
                                        <span style="color:red;">默认：</span>
                            <img class="img-responsive img-thumbnail" style="width:150px;" src="http://we10.66bbn.com/addons/hc_step/bg1.png"/>
                                </div>
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