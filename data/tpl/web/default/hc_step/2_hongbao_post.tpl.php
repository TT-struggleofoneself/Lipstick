<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.dag,.dagtwo,.dagthree,.dagfor{
		display:none;
	}
</style>
<ul class="nav nav-tabs">

	<li><a href="<?php  echo $this->createWebUrl('hongbao')?>">步数币礼包列表</a></li>

	<li class="active"><a href="<?php  echo $this->createWebUrl('hongbao_post')?>">添加步数币礼包</a></li>

	<li><a href="<?php  echo $this->createWebUrl('hongbaoset')?>">基础设置</a></li>

</ul>



<div class="clearfix">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()">
		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				步数币礼包设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">开启顺序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="displayorder" class="form-control" value="<?php  echo $info['displayorder'];?>">
					</div>
					<div class="col-sm-9 col-xs-12">
						<span class="help-block" style="padding-left: 83px;">例如添加五个邀请送币活动，则五个邀请送币活动的开启顺序按1到5填写。送币面额自由设置，最好由小到大填写。用户分享给一个新用户，则可以开启顺序为1的步数币礼包。邀请第二个新用户，就会开启顺序为2的步数币礼包，以此类推。</span>
					</div>
				</div>
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>步数币礼包名称</label>
					<div class="col-sm-4 col-xs-12">
						<input type="text" id="hongbaoname" name="hongbaoname" class="form-control" value="<?php  echo $info['hongbaoname'];?>">
					</div>
					<div class="col-sm-6">
			            <?php  echo tpl_form_field_color('hongbaonamecolor', $value = $info['hongbaonamecolor'])?>
			            <span class="help-block"></span>
			        </div>
                    <div class="col-sm-9 col-xs-12">
						<span class="help-block" style="padding-left: 83px;">例：送一个步数币。</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>步数币礼包金额</label>
					<div class="col-sm-4 col-xs-12">
						<input type="text" id="hongbaomoney" name="hongbaomoney" class="form-control" value="<?php  echo $info['hongbaomoney'];?>">
					</div>
                    <div class="col-sm-9 col-xs-12">
						<span class="help-block" style="padding-left: 83px;color:red;">此处需要与“步数币礼包名称”一致。名称填写“送1个步数币”，则这里填写“1”。</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">送币图片</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_image('hongbaopic',$info['hongbaopic']);?>
						<span class="help-block">图标图片，建议270*320</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="enabled" <?php  if($info['enabled'] == 1) { ?> checked <?php  } ?> value="1"> 是
						</label>
						<label class="radio-inline" >
							<input type="radio" name="enabled" value="0" <?php  if($info['enabled'] != 1) { ?> checked <?php  } ?>> 否
						</label>
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
