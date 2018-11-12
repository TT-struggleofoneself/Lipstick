<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<ul class="nav nav-tabs">

	<li><a href="<?php  echo $this->createWebUrl('awards')?>">奖品列表</a></li>

	<li class="active"><a href="<?php  echo $this->createWebUrl('addawards')?>">添加奖品</a></li>

</ul>



<div class="clearfix">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()" style="">

		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">

		<div class="panel panel-default" style="">

			<div class="panel-heading">

				编辑奖品

			</div>

			<div class="panel-body" style="">

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>奖品名</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="goods_name" id="advname" class="form-control" value="<?php  echo $info['goods_name'];?>">
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label"><span style="color:red">*</span>奖品图</label>
					<div class="col-sm-8">
						<?php  echo tpl_form_field_image('main_img',$info['main_img']);?>
						<div style="color: red !important;">建议比例：600*600 。
					    </div>
					</div>
				</div>

				<div class="form-group">
	                <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>奖品图集</label>
	                <div class="col-sm-9">
	                    <?php  echo tpl_form_field_multi_image('goods_img',$info['goods_img'])?>
	                    <div class='help-block'>奖品详情轮播图</div>
	                </div>
                </div>

                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>价格</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="price" id="advname" class="form-control" value="<?php  echo $info['price'];?>">
					</div>
				</div>

                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>库存</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="inventory" id="advname" class="form-control" value="<?php  echo $info['inventory'];?>">
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>快递说明</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="express" id="advname" class="form-control" value="<?php  echo $info['express'];?>">
						<div class='help-block'>如：全国包邮</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否可以抽中</label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="status"<?php  if($info['status'] == 1) { ?> checked <?php  } ?> value="1"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" name="status" value="0"  <?php  if($info['status'] != 1) { ?> checked <?php  } ?>> 否
						</label>
					</div>
				</div>

			</div>

		</div>

		<div class="form-group col-sm-12">

			<?php  if(empty($info['id'])) { ?>

				<input type="hidden" name="act" value="add">

			<?php  } else { ?>

				<input type="hidden" name="act" value="edit">

			<?php  } ?>

			<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1">

		</div>

	</form>

</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>