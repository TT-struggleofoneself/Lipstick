<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">
    <li><a href="<?php  echo $this->createWebUrl('setting')?>">基础设置</a></li>
    <li><a href="<?php  echo $this->createWebUrl('guanzhu')?>">关注设置</a></li>
    <li><a href="<?php  echo $this->createWebUrl('shenhe')?>">审核设置</a></li>
    <li><a href="<?php  echo $this->createWebUrl('ad')?>">流量主</a></li>
    <li><a href="<?php  echo $this->createWebUrl('poster')?>">海报设置</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('message')?>">模板消息</a></li>
    <li><a href="<?php  echo $this->createWebUrl('signin')?>">签到设置</a></li>
</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-heading">
				模板设置
			</div>
		<div class="panel-body">
            <div class="form-group">
                <div class="col-sm-9">
                    <div class='help-block'>预约成功的模板消息，需要每天手动在此发放。如有其他的消息需求，可自行在微信公众平台后台添加消息模板填到此处发送</div>
                </div>
            </div>
            <div class="form-group">           
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">模板消息ID</label>
                <div class="col-sm-9">
                    <input class='form-control' name='msgid' value="<?php  echo $setup['msgid'];?>">
                    <div class='help-block'>例：小程序后台模板库选择“预约成功通知”<span style="color:red">按顺序</span>勾选预约时间、预约项目、预约状态。复制模板ID填到此处</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">keyword1</label>
                <div class="col-sm-9">
                    <input class='form-control' name='keyword1' value="<?php  echo $setup['keyword1'];?>">
                    <div class='help-block'>例：下午三点了，你的步数达标了吗</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">keyword2</label>
                <div class="col-sm-9">
                    <input class='form-control' name='keyword2' value="<?php  echo $setup['keyword2'];?>">
                    <div class='help-block'>例：步步换宝贝步数挑战</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">keyword3</label>
                <div class="col-sm-9">
                    <input class='form-control' name='keyword3' value="<?php  echo $setup['keyword3'];?>">
                    <div class='help-block'>例：预约成功</div>
                </div>
            </div>
            <div class="form-group">           
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">发货提醒模板消息ID</label>
                <div class="col-sm-9">
                    <input class='form-control' name='fahuomsgid' value="<?php  echo $setup['fahuomsgid'];?>">
                    <div class='help-block'>例：小程序后台模板库选择“订单发货提醒”<span style="color:red">按顺序</span>勾选快递公司、发货时间
、物品名称、运单号。复制模板ID填到此处</div>
                </div>
            </div>
        </div>
		</div>
		<div class="form-group col-sm-12">
			<input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1" <?php  if($update_status == 1) { ?>disabled<?php  } ?>>
			<input type="hidden" name="act" value="add" />
		</div>
		<div class="form-group col-sm-3" style="float:left;">
                <a onclick="closeWindow('./index.php?c=site&amp;a=entry&amp;xuni=1&amp;do=msg&amp;m=hc_step')" class="btn btn-success btn-sm">发送</a>
        </div>
	</form>
</div>



<script>

    function closeWindow(url)
    {
        var iWidth=300;//弹出窗口的宽度;
        var iHeight=150;//弹出窗口的高度;
        var iTop = (window.screen.availHeight - 30 - iHeight) / 2; //获得窗口的垂直位置
        var iLeft = (window.screen.availWidth - 10 - iWidth) / 2; //获得窗口的水平位置
        myWindow = window.open(url,"myWindow",'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');
        // myWindow.document.write("运行中，请稍后...");
        //window.opener=null;
        window.setTimeout(function(){
            // myWindow.close();
            // location.href="<?php  echo $this->createWebUrl('jichu')?>"
        }, 500);

    }
     $('.xuanze img').click(function(index){
     	  	var This = $(this).index();
     		$('.uifg').val(This);
    	$(this).addClass('quia').siblings().removeClass('quia');
    })
          $('.xuanzeone img').click(function(index){
     	  	var This = $(this).index();
     		$('.uifgone').val(This);
    	$(this).addClass('quia').siblings().removeClass('quia');
    })
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>