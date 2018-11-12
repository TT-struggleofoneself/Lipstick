<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>



<div class="clearfix">				

<ul class="nav nav-tabs">

	<li class="active"><a href="<?php  echo $this->createWebUrl('hongbao')?>">步数币礼包列表</a></li>

	<li><a href="<?php  echo $this->createWebUrl('hongbao_post')?>">添加步数币礼包</a></li>

	<li><a href="<?php  echo $this->createWebUrl('hongbaoset')?>">基础设置</a></li>

</ul>

<div class="main panel panel-default">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:15%;">图标</th>
					<th style="width:10%;">排序</th>					
					<th style="width:20%;">标题</th>
					<th style="width:20%;">送币金额</th>
					<!-- <th style="width:10%;">商品/分类ID</th> -->
					<th style="width:10%;">排序</th>
					<th style="width:10%;">状态</th>
					<th class="text-right" style="width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
					<tr>
						<td><img class="scrollLoading" src="<?php  echo tomedia($item['hongbaopic']);?>" data-url="<?php  echo tomedia($item['hongbaopic']);?>" onerror="this.src='/web/resource/images/nopic-small.jpg'" height="50" width="50"></td>
						<td><?php  echo $item['displayorder'];?></td>
						<td><?php  echo $item['hongbaoname'];?></td>
						<td><?php  echo $item['hongbaomoney'];?></td>
						<td><input type="text" class="form-control displayorder" data-id="<?php  echo $item['id'];?>" name="displayorder" value="<?php  echo $item['displayorder'];?>"></td>
						<td>
							<?php  if($item['enabled'] == 1) { ?>
							<span class="label label-success">显示</span>
							<?php  } else { ?>
							<span class="label label-default">隐藏</span>
							<?php  } ?>
						</td>
						<td class="text-right">
							<a href="<?php  echo $this->createWebUrl('hongbao_post',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="修改"><i class="fa fa-edit"></i></a>
							<a href="<?php  echo $this->createWebUrl('hongbao_post',array('act'=>'del','id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i></a>
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
<script type="text/javascript">
$(".displayorder").bind('input propertychange',function(){
	$.ajax({
	    url:"<?php  echo $this->createWebUrl('hongbao_post',array('act'=>'display'))?>",
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