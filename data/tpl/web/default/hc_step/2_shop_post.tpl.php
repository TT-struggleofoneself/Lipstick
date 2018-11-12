<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.dag,.dagtwo,.dagthree,.dagfor{
		display:none;
	}
</style>
<ul class="nav nav-tabs">

	<li><a href="<?php  echo $this->createWebUrl('shop')?>">门店列表</a></li>

	<li class="active"><a href="<?php  echo $this->createWebUrl('shop_post')?>">添加门店</a></li>

	<li><a href="<?php  echo $this->createWebUrl('hexiaolog')?>">核销记录</a></li>

</ul>
<div class="clearfix">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()">
		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				门店设置
			</div>
			<div class="panel-body">
			    <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>店员UID</label>
					<div class="col-sm-4 col-xs-12">
						<input type="text" id="hongbaoname" name="user_id" class="form-control" value="<?php  echo $info['user_id'];?>">
						<span class="help-block" style="color:red;">请去“用户列表”查询核销员的UID，填写到此处。成为核销员之后，会在小程序“我的”页面右上角出现“扫一扫”功能。</span>
					</div>
				</div>
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店名称</label>
					<div class="col-sm-4 col-xs-12">
						<input type="text" id="hongbaoname" name="shopname" class="form-control" value="<?php  echo $info['shopname'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">门店LOGO</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_image('logo',$info['logo']);?>
						<span class="help-block">门店logo，建议480*480</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">门店大图</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_image('topbg',$info['topbg']);?>
						<span class="help-block">建议800*600</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">联系电话</label>
					<div class="col-sm-4 col-xs-12">
						<input type="text" id="hongbaoname" name="tel" class="form-control" value="<?php  echo $info['tel'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">门店地址</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_district('dizhi',array('province' => $info['sheng'],'city' => $info['shi'],'district' => $info['qu']));?>
					</div>
				</div>
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">详细地址</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id="hongbaoname" name="address" class="form-control" value="<?php  echo $info['address'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-6 col-sm-3 col-md-2 control-label">营业开始时间</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_clock('starttime',$info['starttime'], $withtime = false);?>
					</div>					
				</div>
				<div class="form-group">
					<label class="col-xs-6 col-sm-3 col-md-2 control-label">营业结束时间</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_clock('endtime',$info['endtime'], $withtime = false);?>
					</div>					
				</div>
				
			</div>
		</div>
		<div class="form-group col-sm-12">

			<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1">

			<?php  if(empty($info['id'])) { ?>

				<input type="hidden" name="act" value="add">

			<?php  } else { ?>

				<input type="hidden" name="act" value="edit">

			<?php  } ?>

		</div>

	</form>
<!-- <script type="text/javascript">
	$('#qie').find('label').each(function(){			
		$(this).click(function(){
			var index=$(this).index()
			if(index==0){
				$('.one').hide()
				$('.two').hide()
				$('.three').show()
			}else if(index==1){
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
			}
			else if(index==2){
				$('.one').show()
				$('.two').show()
				$('.three').hide()
			}
		})
	})
	var index = $('#qie label').find(':radio:checked').val();
	        if(index==0){
				$('.one').hide()
				$('.two').hide()
				$('.three').show()
			}else if(index==1){
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
			}
			else if(index==2){
				$('.one').show()
				$('.two').show()
				$('.three').hide()
			}
</script> -->

</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
