<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">

    <li><a href="<?php  echo $this->createWebUrl('shop')?>">门店列表</a></li>

    <li><a href="<?php  echo $this->createWebUrl('shop_post')?>">添加门店</a></li>

    <li class="active"><a href="<?php  echo $this->createWebUrl('hexiaolog')?>">核销记录</a></li>

</ul>
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
                        <th>uid</th>
                        <th>用户名</th>
                        <th>头像</th>                       
                        <th>商品名</th>
                        <th>店铺名</th>
                        <th>店员uid</th>
                        <th>兑换时间</th>
                        <th>核销时间</th>
                        <th>核销状态</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                         <td><div class="type-parent"><?php  echo $item['user_id'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['nick_name'];?></div></td>
                         <td><div class="type-parent"><img src="<?php  echo tomedia($item['head_pic']);?>" width="50" height="50" /></div></td>
                         <td><div class="type-parent"><?php  echo $item['goodsname'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['shopname'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['shop_userid'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['time'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['hexiaotime'];?></div></td>
                         <td><div class="type-parent">
                            <?php  if($item['hexiaostatus'] == 1) { ?>
                            <span class="label label-success">已核销</span>
                            <?php  } else { ?>
                            <span class="label label-default">未核销</span>
                            <?php  } ?>
                         </div></td>
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
        </form>
    </div>
</div>
<style type="text/css">
    .hide{display: none}
</style>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>