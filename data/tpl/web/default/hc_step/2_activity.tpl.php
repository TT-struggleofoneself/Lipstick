<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>



<div class="clearfix">				

<ul class="nav nav-tabs">

	<li class="active"><a href="<?php  echo $this->createWebUrl('activity')?>">活动</a></li>

	<li><a href="<?php  echo $this->createWebUrl('activity_post')?>">添加活动</a></li>

	<li><a href="<?php  echo $this->createWebUrl('activityset')?>">基础设置</a></li>


</ul>

<div class="main panel panel-default">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:15%;">id</th>
					<th style="width:10%;">排序</th>					
					<th style="width:20%;">步数</th>
					<th style="width:10%;">报名费</th>
					<!-- <th style="width:10%;">开始时间</th>
					<th style="width:10%;">结束时间</th> -->
					<th class="text-right" style="width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
					<tr>
						<td><?php  echo $item['id'];?></td>
						<td><input type="text" class="form-control displayorder" data-id="<?php  echo $item['id'];?>" name="displayorder" value="<?php  echo $item['displayorder'];?>"></td>
						<td><?php  echo $item['step'];?></td>
						<td><?php  echo $item['entryfee'];?></td>
						<!-- <td><?php  echo $item['starttime'];?></td>
						<td><?php  echo $item['endtime'];?></td> -->
						<td class="text-right">
						    <a href="<?php  echo $this->createWebUrl('activitylog',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="活动记录">记录</a>
							<a href="<?php  echo $this->createWebUrl('activity_post',array('id'=>$item['id']))?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="修改"><i class="fa fa-edit"></i></a>
							<a href="<?php  echo $this->createWebUrl('activity_post',array('act'=>'del','id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i></a>
						</td>
					</tr>
				<?php  } } ?>
				<?php  if(empty($list) ) { ?>
                <tr ng-if="!wechats">
                <td colspan="9" class="text-center">暂无数据</td>
                </tr>
                <?php  } ?>
				<tr>
					<td colspan="7" style="text-align:right"><?php  echo $page;?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
$(".displayorder").bind('input propertychange',function(){
	$.ajax({
	    url:"<?php  echo $this->createWebUrl('activity_post',array('act'=>'display'))?>",
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