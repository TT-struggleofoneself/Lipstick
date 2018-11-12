<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<div class="clearfix">
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo $this->createWebUrl('goods')?>">商品列表</a></li>
	<li><a href="<?php  echo $this->createWebUrl('addgoods')?>">添加商品</a></li>
</ul>

<div class="main panel panel-default">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:5%;" class="text-center">id</th>
					<th style="width:20%;" class="text-center">商品名</th>
					<th style="width:20%;" class="text-center">商品图</th>
					<th style="width:10%;" class="text-center">专柜价格</th>
					<th style="width:10%;" class="text-center">购买价格</th>
					<th style="width:10%;" class="text-center">抽奖价格</th>
					<th style="width:10%;" class="text-center">库存</th>
					<th style="width:10%;" class="text-center">排序</th>
					<th style="width:10%;" class="text-center">状态</th>
					<th class="text-right" style="width:15%;" class="text-center">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
				<tr>
					<td class="text-center"><?php  echo $item['id'];?></td>
					<td class="text-center"><?php  echo $item['goods_name'];?></td>
					<td class="text-center"><img class="scrollLoading" src="<?php  echo tomedia($item['main_img']);?>" data-url="<?php  echo tomedia($item['main_img']);?>"  height="50" width="50"></td>
					<td class="text-center">
						<?php  echo $item['price2'];?>
					</td>
					<td class="text-center">
						<?php  echo $item['price'];?>
					</td>
					<td class="text-center">
						<?php  echo $item['rmb'];?>
					</td>
					<td class="text-center"><?php  echo $item['inventory'];?></td>
					<td><input type="text" class="form-control displayorder" data-id="<?php  echo $item['id'];?>" name="displayorder" value="<?php  echo $item['displayorder'];?>"></td>
					<td class="text-center">
					    <?php  if($item['status'] == 1) { ?>
							<span class="label label-success">上架</span>
							<?php  } else { ?>
							<span class="label label-default">下架</span>
						<?php  } ?>
					</td>
					<td class="text-right">
						<a href="<?php  echo $this->createWebUrl('addgoods',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('addgoods',array('act'=>'del','id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
				<?php  if(empty($list) ) { ?>
                <tr ng-if="!wechats">
                <td colspan="9" class="text-center">暂无数据</td>
                </tr>
                <?php  } ?>

                <tr>
					<td colspan="9" style="text-align:right"><?php  echo $page;?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script>
	require(['bootstrap'],function($){
		$('.btn').hover(function(){
			$(this).tooltip('show');
		},function(){
			$(this).tooltip('hide');
		});
	});
</script>
<script type="text/javascript">
$(".displayorder").bind('input propertychange',function(){
	$.ajax({
	    url:"<?php  echo $this->createWebUrl('addgoods',array('act'=>'display'))?>",
	    type:'POST',
	    async:true,
	    data:{
	        displayorder:$(this).val(),id:$(this).attr('data-id')
	    },
	    timeout:5000,
	    dataType:'json',
	    success:function(data){
	        //alert(data.message);
	    }
	})
})
</script>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>