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
	<li><a href="<?php  echo $this->createWebUrl('exchange')?>">商品兑换记录</a></li>
	<li><a href="<?php  echo $this->createWebUrl('win_exchange')?>">奖品兑换记录</a></li>
	<li><a href="<?php  echo $this->createWebUrl('coin_exchange')?>">步数兑换记录</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('xuni')?>">虚拟兑换记录</a></li>
    <li><a href="<?php  echo $this->createWebUrl('xuni_post')?>">添加虚拟兑换记录</a></li>
</ul>
<div class="clearfix">

	<div class="panel panel-default">

		<div class="table-responsive">

			<table class="table table-hover">

				<thead class="navbar-inner">
					<tr>
						<th style="width:18%;" class="text-center">ID</th>
						<th style="width:18%;" class="text-center">头像</th>					
						<th style="width:18%;" class="text-center">昵称</th>
						<th style="width:18%;" class="text-center">商品</th>
						<th style="width:18%;" class="text-center">购买时间</th>
						<th style="width:18%;" class="text-center">操作</th>
					</tr>
				</thead>

				<tbody>

				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>

					<tr id="<?php  echo $item['id'];?>">
						<td class="text-center"><?php  echo $item['id'];?></td>
						<td class="text-center"><img class="scrollLoading" src="<?php  echo tomedia($item['head_pic']);?>" data-url="<?php  echo tomedia($item['head_pic']);?>"  height="50" width="50"></td>					
						<td class="text-center"><?php  echo $item['nick_name'];?></td>
						<td class="text-center"><?php  echo $item['goods_name'];?></td>
						<td class="text-center"><?php  echo $item['time'];?></td>
						<td class="text-right">
						<a href="<?php  echo $this->createWebUrl('xuni_post',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('xuni_post',array('act'=>'del','id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i></a>
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