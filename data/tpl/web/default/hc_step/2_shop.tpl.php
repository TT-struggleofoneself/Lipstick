<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>



<div class="clearfix">				

<ul class="nav nav-tabs">

	<li class="active"><a href="<?php  echo $this->createWebUrl('shop')?>">门店列表</a></li>

	<li><a href="<?php  echo $this->createWebUrl('shop_post')?>">添加门店</a></li>

	<li><a href="<?php  echo $this->createWebUrl('hexiaolog')?>">核销记录</a></li>

</ul>

<div class="main panel panel-default">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:10%;">logo</th>
					<th style="width:10%;">门店名称</th>					
					<th style="width:20%;">地址</th>
					<th style="width:10%;">营业时间</th>
					<th style="width:10%;">联系电话</th>
					<th style="width:10%;">店员昵称</th>
					<th style="width:10%;">店员uid</th>
					<th style="width:10%;">头像</th>
					<th class="text-right" style="width:10%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
					<tr>
						<td><img class="scrollLoading" src="<?php  echo tomedia($item['logo']);?>" data-url="<?php  echo tomedia($item['logo']);?>" onerror="this.src='/web/resource/images/nopic-small.jpg'" height="50" width="50"></td>
						<td><?php  echo $item['shopname'];?></td>
						<td><?php  echo $item['sheng'];?><?php  echo $item['shi'];?><?php  echo $item['qu'];?><?php  echo $item['address'];?></td>
						<td><?php  echo $item['starttime'];?>--<?php  echo $item['endtime'];?></td>
						<td><?php  echo $item['tel'];?></td>
						<td><?php  echo $item['nick_name'];?></td>
						<td><?php  echo $item['user_id'];?></td>
						<td><img class="scrollLoading" src="<?php  echo tomedia($item['head_pic']);?>" data-url="<?php  echo tomedia($item['head_pic']);?>" onerror="this.src='/web/resource/images/nopic-small.jpg'" height="50" width="50"></td>
						<td class="text-right">
							<a href="<?php  echo $this->createWebUrl('shop_post',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="修改"><i class="fa fa-edit"></i></a>
							<a href="<?php  echo $this->createWebUrl('shop_post',array('act'=>'del','id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i></a>
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
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>