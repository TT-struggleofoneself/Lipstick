<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<div class="clearfix">
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo $this->createWebUrl('question')?>">问题列表</a></li>
	<li><a href="<?php  echo $this->createWebUrl('question_post')?>">添加常见问题</a></li>
	<li><a href="<?php  echo $this->createWebUrl('question_set')?>">基础设置</a></li>
</ul>

<div class="main panel panel-default">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:5%;">id</th>
					<th style="width:20%;">标题</th>
					<th style="width:10%;">状态</th>
					<th style="width:20%;">添加时间</th>
					<th class="text-right" style="width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
				<tr>
					<td><?php  echo $item['id'];?></td>
					<td><?php  echo $item['title'];?></td>
					<td>
					    <?php  if($item['enabled'] == 1) { ?>
							<span class="label label-success">显示</span>
							<?php  } else { ?>
							<span class="label label-default">隐藏</span>
						<?php  } ?>
					</td>
					<td><?php  echo $item['createtime'];?></td>
					<td class="text-right">
						<a href="<?php  echo $this->createWebUrl('question_post',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('question_post',array('act'=>'del','id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
				<?php  if(empty($list) ) { ?>
                <tr ng-if="!wechats">
                <td colspan="9" class="text-center">暂无数据</td>
                </tr>
                <?php  } ?>
                <tr>
                    <td colspan="6" style="text-align:right"><?php  echo $page;?></td>
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
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>