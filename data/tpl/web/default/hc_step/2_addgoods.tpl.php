<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<ul class="nav nav-tabs">

	<li><a href="<?php  echo $this->createWebUrl('goods')?>">商品列表</a></li>

	<li class="active"><a href="<?php  echo $this->createWebUrl('addgoods')?>">添加商品</a></li>

</ul>


<div class="clearfix">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()" style="">

		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">

		<div class="panel panel-default" style="">

			<div class="panel-heading">
				编辑商品

			</div>

			<div class="panel-body" style="">

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>商品名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="goods_name" id="advname" class="form-control" value="<?php  echo $info['goods_name'];?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>品牌名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="brand" id="advname" class="form-control" value="<?php  echo $info['brand'];?>">
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label"><span style="color:red">*</span>商品图</label>
					<div class="col-sm-8">
						<?php  echo tpl_form_field_image('main_img',$info['main_img']);?>
						<div style="color: red !important;">建议比例：600*600 。
					    </div>
					</div>
				</div>
				<!--
				<div class="form-group">
	                <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>商品图集</label>
	                <div class="col-sm-9">
	                    <?php  echo tpl_form_field_multi_image('goods_img',$info['goods_img'])?>
	                    <div class='help-block'>商品详情轮播图</div>
	                </div>
                </div>
                -->
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">出售方式</label>
					<div class="col-sm-9 col-xs-12" id="huan">
						<label class="radio-inline">
							<input type="radio" name="selltype" <?php  if($info['selltype'] == 0) { ?> checked <?php  } ?> value="0">普通
						</label>
						<label class="radio-inline">
							<input type="radio" name="selltype" value="1"  <?php  if($info['selltype'] == 1) { ?> checked <?php  } ?>>核销
						</label>
					<!--
						<label class="radio-inline">
							<input type="radio" name="selltype" value="2"  <?php  if($info['selltype'] == 2) { ?> checked <?php  } ?>>邀请满x好友得商品
						</label>
						-->
					</div>
				</div>

				<!--
				<div class="form-group shop">
	                <label class="col-xs-6 col-sm-3 col-md-2 control-label">门店选择</label>
					<div class="col-md-6 mathyts ">
	                       <select name="shop_id" class="form-control form-control_s" >
	                           <option value="" <?php  if($info['shop_id']== '') { ?>selected<?php  } ?>>门店列表</option>
	                           <?php  if(is_array($shop)) { foreach($shop as $index => $data) { ?>                           
	                           <option value="<?php  echo $data['id'];?>" <?php  if($info['shop_id'] == $data['id']) { ?>selected<?php  } ?>><?php  echo $data['shopname'];?></option>
	                           <?php  } } ?>
	                       </select>
	                </div>	
                </div>
                -->
                <input type="hidden" name="paytype" value="1">

                <!--
                <div class="form-group type">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">支付方式</label>
					<div class="col-sm-9 col-xs-12" id="qie">
						<label class="radio-inline">
							<input type="radio" name="paytype" <?php  if($info['paytype'] == 0) { ?> checked <?php  } ?> value="0">步数币
						</label>
						<label class="radio-inline">
							<input type="radio" name="paytype" value="1"  <?php  if($info['paytype'] == 1) { ?> checked <?php  } ?>>步数币+人民币
						</label>
					</div>
				</div>
					-->

					<!---
				<div class="form-group people">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>邀请满x新人可以免费兑换商品</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="minpeople" id="advname" class="form-control" value="<?php  echo $info['minpeople'];?>">
					</div>
				</div>
				->

                <div class="form-group one">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>价格</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="price" id="advname" class="form-control" value="<?php  echo $info['price'];?>">
					</div>
				</div>

				<!--
				<div class="form-group two">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>价格(步数币)</label>
					<div class="col-sm-3 col-xs-12">
						<input type="text" id="advname" name="price2" class="form-control" value="<?php  echo $info['price2'];?>">
					</div>
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>人民币</label>
					<div class="col-sm-3 col-xs-12">
						<input type="text" id="advname" name="rmb" class="form-control" value="<?php  echo $info['rmb'];?>">
					</div>
				</div>
				-->


				<div class="form-group three">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>专柜价</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="price2" id="advname" class="form-control" value="<?php  echo $info['price2'];?>">
					</div>
				</div>
				
				<div class="form-group three">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>购买价</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="price" id="advname" class="form-control" value="<?php  echo $info['price'];?>">
					</div>
				</div>

				<div class="form-group three">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>单次抽奖（单次）价格</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="rmb" id="advname" class="form-control" value="<?php  echo $info['rmb'];?>">
					</div>
				</div>

                
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>每人最大购买次数</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="maxbuy" id="advname" class="form-control" value="<?php  echo $info['maxbuy'];?>">
						<div class='help-block'>0为不限制购买次数。邀请好友模式默认只能兑换一次</div>
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

				<!--
				<div class="form-group" style="">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">商品详情</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_ueditor('goodsinfo',$info['goodsinfo']);?>
						</div>
				    </div>
				  -->

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="displayorder" class="form-control" value="<?php  echo $info['displayorder'];?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否上架</label>
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

<script type="text/javascript">
	$('#qie').find('label').each(function(){			
		$(this).click(function(){
			var index=$(this).index()
			if(index==0){
				$('.one').show()
				$('.two').hide()
				$('.three').hide()
			}else if(index==1){
				$('.one').hide()
				$('.two').show()
				$('.three').show()
			}
		})
	})
	var index = $('#qie label').find(':radio:checked').val();
	        if(index==0){
				$('.one').show()
				$('.two').hide()
				$('.three').hide()
			}else if(index==1){
				$('.one').hide()
				$('.two').show()
				$('.three').show()
			}
</script>
<script type="text/javascript">
	$('#huan').find('label').each(function(){			
		$(this).click(function(){
			var index=$(this).index()
			if(index==0){
				$('.shop').hide()
				$('.people').hide()
				$('.type').show()
				/*$('.one').hide()
				$('.two').hide()
				$('.three').hide()*/
			}else if(index==1){
				$('.shop').show()
				$('.people').hide()
				$('.type').show()
				/*$('.one').hide()
				$('.two').hide()
				$('.three').hide()*/
			}
			else if(index==2){
				$('.shop').hide()
				$('.people').show()
				$('.type').hide()
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
			}
		})
	})
	var index = $('#huan label').find(':radio:checked').val();
	        if(index==0){
				$('.shop').hide()
				$('.people').hide()
				$('.type').show()
				/*$('.one').hide()
				$('.two').hide()
				$('.three').hide()*/
			}else if(index==1){
				$('.shop').show()
				$('.people').hide()
				$('.type').show()
				/*$('.one').hide()
				$('.two').hide()
				$('.three').hide()*/
			}
			else if(index==2){
				$('.shop').hide()
				$('.people').show()
				$('.type').hide()
				$('.one').hide()
				$('.two').hide()
				$('.three').hide()
			}
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>