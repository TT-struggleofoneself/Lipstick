<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('exchange')?>">商品兑换记录</a></li>
	<li><a href="<?php  echo $this->createWebUrl('win_exchange')?>">奖品兑换记录</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('coin_exchange')?>">步数兑换记录</a></li>
    <li><a href="<?php  echo $this->createWebUrl('xuni')?>">虚拟兑换记录</a></li>
    <li><a href="<?php  echo $this->createWebUrl('xuni_post')?>">添加虚拟兑换记录</a></li>
</ul>
<div class="panel panel-default">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="" method="post" class="form-horizontal" role="form" id="form">
                <div class="form-group">
                    <label class="col-md-2 control-label">UID</label>
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
    <div class="panel-body">
       <form action="" method="post" class="form-horizontal form">
       <div class="alert alert-info url_div hide" role="alert">
       </div>
            <input type="hidden" name="storeid" value="">
            <div class="table-responsive panel-body">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th>id</th>
                        <th>uid</th>
                        <th>用户名</th>
                        <th>头像</th>                       
                        <th>步数</th>
                        <th>步数币</th>
                        <th>兑换时间</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                         <td><div class="type-parent"><?php  echo $item['id'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['user_id'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['nick_name'];?></div></td>
                         <td><div class="type-parent"><img src="<?php  echo tomedia($item['head_pic']);?>" width="50" height="50" /></div></td>
                         <td><div class="type-parent"><?php  echo $item['bushu'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['money'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['timestamp'];?></div></td>
                    </tr>
                    <?php  } } ?>
                    <?php  if(empty($list) ) { ?>
	                <tr ng-if="!wechats">
	                <td colspan="15" class="text-center">暂无数据</td>
	                </tr>
	                <?php  } ?>
                    <tr>
                    <td colspan="15" style="text-align:right"><?php  echo $page;?></td>
                    </tr>     
                    </tbody>
                </table>  
            </div>
        </form>
    </div>
</div>
<style type="text/css">
    .hide{display: none}
</style>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>