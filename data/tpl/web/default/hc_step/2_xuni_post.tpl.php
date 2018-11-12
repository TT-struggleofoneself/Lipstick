<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('exchange')?>">商品兑换记录</a></li>
	<li><a href="<?php  echo $this->createWebUrl('win_exchange')?>">奖品兑换记录</a></li>
	<li><a href="<?php  echo $this->createWebUrl('coin_exchange')?>">步数兑换记录</a></li>
    <li><a href="<?php  echo $this->createWebUrl('xuni')?>">虚拟兑换记录</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('xuni_post')?>">添加虚拟兑换记录</a></li>
</ul>
<div class="clearfix">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()" style="">
		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">
		<div class="panel panel-default" style="">
			<div class="panel-heading">
				添加
			</div>
			<div class="panel-body">
			<div class="form-group">
				<label class="col-xs-6 col-sm-3 col-md-2 control-label">加虚拟记录的商品</label>
				<div class="col-md-6 mathyts ">
                       <select name="goods_id" class="form-control form-control_s" >
                           <option value="" <?php  if($info['goos_id']== '') { ?>selected<?php  } ?>>商品列表</option>
                           <?php  if(is_array($goods)) { foreach($goods as $index => $data) { ?>                           
                           <option value="<?php  echo $data['id'];?>" <?php  if($info['goods_id'] == $data['id']) { ?>selected<?php  } ?>><?php  echo $data['goods_name'];?></option>
                           <?php  } } ?>
                       </select>
                </div>					
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户昵称</label>
				<div class="col-sm-9 col-xs-12">
					<input type="text" name="nick_name" id="advname" class="form-control" value="<?php  echo $info['nick_name'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">用户头像</label>
				<div class="col-sm-8">
					<?php  echo tpl_form_field_image('head_pic',$info['head_pic']);?>
					<div style="color: red !important;">建议尺寸：80*80
				    </div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-6 col-sm-3 col-md-2 control-label">购买时间</label>
				<div class="col-sm-9 col-xs-12">
					 <?php  echo tpl_form_field_date('time',$info['time'], $withtime = true);?>
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