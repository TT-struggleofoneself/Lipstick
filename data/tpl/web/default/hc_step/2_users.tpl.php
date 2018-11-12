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
	<li class="active"><a href="<?php  echo $this->createWebUrl('users')?>">用户列表</a></li>
	<li><a href="<?php  echo $this->createWebUrl('blacklist')?>">黑名单</a></li>
</ul>
<div class="clearfix">

	<div class="panel panel-default">

		<div class="panel-heading">筛选</div>

		<div class="panel-body">

			<form action="" method="post" class="form-horizontal" role="form" id="form">

				<div class="form-group">

					<div class="col-md-4 mathyts" >
                       <select  name="order_status" class="form-control form-control_s" >                        
                           <option value="1" <?php  if($order_status==1) { ?>selected<?php  } ?>>昵称</option>
                           <option value="2" <?php  if($order_status==2) { ?>selected<?php  } ?>>id</option>
                       </select>
                    </div>
					<div class="col-md-5">
						<input type="text" class="form-control" name="keyword" id="keyword" value="<?php  echo $keyword;?>">
					</div>

					<div class="pull-right col-md-2">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
					</div>

				</div>

			</form>

		</div>

		<div style="color: red !important;margin: 10px 100px;">拉黑功能说明1：如果发现用户多次恶意刷步，恶意兑换，可以采用拉黑功能！由于拉黑会突破微信代码，当移除黑名单无法获取微信步数属于正常情况，谨慎使用。

拉黑功能说明2：安卓手机用户拉黑之后，会出现闪退；苹果手机用户拉黑之后，会出现频繁跳转空白页面。是正常的拉黑状态</div>

	</div>

	<div class="panel panel-default">

		<div class="table-responsive">

			<table class="table table-hover">

				<thead class="navbar-inner">
					<tr>
						<th style="width:15%;">UID</th>
						<th style="width:15%;">头像</th>					
						<th style="width:15%;">昵称</th>
						<th style="width:15%;">余额</th>
						<th style="width:40%;">操作</th>
					</tr>

				</thead>

				<tbody>

				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
				
					<tr id="<?php  echo $item['user_id'];?>">
						<td><?php  echo $item['user_id'];?></td>
						<td><img src="<?php  echo $item['head_pic'];?>" style="width:50px"></td>					
						<td><?php  echo $item['nick_name'];?></td>
						<td><?php  echo $item['money'];?></td>
						<td>
						<a class="btn btn-primary btn-sm" href="<?php  echo $this->createWebUrl('bushulog', array('user_id' => $item['user_id']))?>">步数记录</a>
						<a class="btn btn-primary btn-sm" href="<?php  echo $this->createWebUrl('invitelog', array('user_id' => $item['user_id']))?>">邀请记录</a>
						<a class="btn btn-primary btn-sm" href="<?php  echo $this->createWebUrl('tiaozhanlog', array('user_id' => $item['user_id']))?>">挑战记录</a>
						<a class="btn btn-danger btn-sm" onclick="return confirm('确认拉黑吗？');return false;" href="<?php  echo $this->createWebUrl('users', array('op' => 'black', 'id' => $item['user_id']))?>">拉黑</a>
						<a class="btn btn-danger btn-sm" onclick="return confirm('确认删除吗？');return false;" href="<?php  echo $this->createWebUrl('users', array('op' => 'del', 'id' => $item['user_id']))?>">删除</a>
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