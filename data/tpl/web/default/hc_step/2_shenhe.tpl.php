<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('setting')?>">基础设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('guanzhu')?>">关注设置</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('shenhe')?>">审核设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('ad')?>">流量主</a></li>
	<li><a href="<?php  echo $this->createWebUrl('poster')?>">海报设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('message')?>">模板消息</a></li>
	<li><a href="<?php  echo $this->createWebUrl('signin')?>">签到设置</a></li>
</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-heading">
				审核设置
			</div>
			<div class="panel-body">
				<div class="tab-pane active" id="tab_param2">
				        <div style="color: red !important;">
				                注意：请仔细阅读下方文字<br>

								1.在审核页面设置中先根据自己的小程序类目编辑好骗审页面<br/>

								2.点击“小程序”—点击“上传微信审核”—扫码后填写版本号并记录下来，新填写的版本号要与之前不同<br/>

								3.在下方审核中的版本号填写审核中的版本号<br/>

								4.这样提交审核的就是“过审页面”，不会影响线上版本的运营。<br/>

								5.等审核通过之后，版本号一定要修改成和线上的版本号不一样。<br/>

								6.以后再更新版本，同时遵循上方的操作。<br/>
				        </div>
				        <div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">审核中版本号</label>
							<div class="col-sm-8">
								<input type="text" name="version" class="form-control" value="<?php  echo $info['version'];?>">
								
							</div>
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


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>