<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.dag,.dagtwo,.dagthree,.dagfor{
		display:none;
	}
</style>
<ul class="nav nav-tabs">

	<li><a href="<?php  echo $this->createWebUrl('adv')?>">幻灯片</a></li>

	<li class="active"><a href="<?php  echo $this->createWebUrl('adv_post')?>">添加幻灯片</a></li>

</ul>



<div class="clearfix">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()">

		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">

		<div class="panel panel-default">

			<div class="panel-heading">

				幻灯片设置

			</div>

			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="displayorder" class="form-control" value="<?php  echo $info['displayorder'];?>">
					</div>
				</div>

				<div class="form-group">

					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>幻灯片标题</label>

					<div class="col-sm-9 col-xs-12">

						<input type="text" id="advname" name="advname" class="form-control" value="<?php  echo $info['advname'];?>">

					</div>

				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">幻灯片图片</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_image('thumb',$info['thumb']);?>
						<span class="help-block">幻灯片图片，建议750*270</span>
					</div>
				</div>



				<div class="form-group shop">
	                <label class="col-xs-6 col-sm-3 col-md-2 control-label">类型</label>
					<div class="col-md-6 mathyts ">
	                       <select name="shop_id" class="form-control form-control_s" >
	                           <option value="" <?php  if($info['type']== '0') { ?>selected<?php  } ?>>启动图</option>
	                           <!--
	                            <option value="" <?php  if($info['type']== '1') { ?>selected<?php  } ?>>首页轮播图</option>
	                             <option value="" <?php  if($info['type']== '2') { ?>selected<?php  } ?>>门店列表</option>
	                              <option value="" <?php  if($info['type']== '3') { ?>selected<?php  } ?>>门店列表</option>
	                           -->
	                       </select>
	                </div>	
                </div>
                

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">跳转方式</label>
					<div class="col-sm-9 col-xs-12" id="qie">
						<label class="radio-inline inlinetab" >
							<input type="radio" name="jump" <?php  if($info['jump'] == 0) { ?> checked <?php  } ?> value="0">不跳转
						</label>
						<label class="radio-inline inlinetab" >
							<input type="radio" name="jump" <?php  if($info['jump'] == 1) { ?> checked <?php  } ?> value="1">跳转其他小程序
						</label>
						<label class="radio-inline inlinetab" >
							<input type="radio" name="jump" <?php  if($info['jump'] == 2) { ?> checked <?php  } ?> value="2">跳转H5
						</label>
						<label class="radio-inline inlinetab" >
							<input type="radio" name="jump" <?php  if($info['jump'] == 3) { ?> checked <?php  } ?> value="3">客服消息
						</label>
					</div>
				</div>

				<div class="form-group one">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">小程序路径</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="xcxpath" class="form-control" value="<?php  echo $info['xcxpath'];?>">
					</div>
				</div>
				<div class="form-group two">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">小程序appid</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="xcxappid" class="form-control" value="<?php  echo $info['xcxappid'];?>">
					</div>
				</div>
				<div class="form-group three">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">H5链接</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="h5" class="form-control" value="<?php  echo $info['h5'];?>">
					</div>
				</div>
				<div class="form-group four">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">客服消息引导图</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo tpl_form_field_image('tippic',$info['tippic']);?>
						<span class="help-block">图标图片，建议1080*1920</span>
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

</div>
<script type="text/javascript">
	$('#qie').find('label').each(function(){			
		$(this).click(function(){
			var index=$(this).index()
			if(index==0){
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
				$('.four').hide()
			}else if(index==1){
				$('.one').show()
				$('.two').show()
				$('.three').hide()
				$('.four').hide()
			}
			else if(index==2){
				$('.one').hide()
				$('.two').hide()
				$('.three').show()
				$('.four').hide()
			}
			else if(index==3){
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
				$('.four').show()
			}
		})
	})
	var index = $('#qie label').find(':radio:checked').val();
	        if(index==0){
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
				$('.four').hide()
			}else if(index==1){
				$('.one').show()
				$('.two').show()
				$('.three').hide()
				$('.four').hide()
			}
			else if(index==2){
				$('.one').hide()
				$('.two').hide()
				$('.three').show()
				$('.four').hide()
			}
			else if(index==3){
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
				$('.four').show()
			}
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
