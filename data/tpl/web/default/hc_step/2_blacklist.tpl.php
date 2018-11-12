<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>


<style>
    .mathyts{
        overflow:hidden;
      
    }
    .mathyts span{
        float:left;
        line-height:36px;
    }
    .form-control_s{
        float:right;
        width:60%;
    }
</style>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('users')?>">用户列表</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('blacklist')?>">黑名单</a></li>
</ul>
<div class="clearfix">

	<div class="panel panel-default">

		<div class="panel-heading">筛选</div>

		<div class="panel-body">

			<form action="" method="post" class="form-horizontal" role="form" id="form">

				<div class="form-group">

					<label class="col-md-2 control-label">昵称</label>
					<div class="col-md-5">
						<input type="text" class="form-control" name="keyword" id="keyword" value="<?php  echo $keyword;?>">
					</div>

					<div class="pull-right col-md-2">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
					</div>

				</div>

			</form>

		</div>

	</div>

	<div class="panel panel-default">

		<div class="table-responsive">

			<table class="table table-hover">

				<thead class="navbar-inner">
					<tr>
						<th style="width:15%;">UID</th>
						<th style="width:15%;">头像</th>					
						<th style="width:15%;">昵称</th>
						<th style="width:20%;">操作</th>
					</tr>

				</thead>

				<tbody>

				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>

					<tr id="<?php  echo $item['user_id'];?>">
						<td><?php  echo $item['user_id'];?></td>
						<td><img src="<?php  echo $item['head_pic'];?>" style="width:50px"></td>					
						<td><?php  echo $item['nick_name'];?></td>
						<td>
						<a class="btn btn-danger btn-sm" onclick="return confirm('确认移除黑名单吗？');return false;" href="<?php  echo $this->createWebUrl('blacklist', array('op' => 'white', 'id' => $item['user_id']))?>">移除黑名单</a>
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

</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>