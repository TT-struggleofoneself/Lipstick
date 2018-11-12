<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/mzhk_sun/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li class="active" ><a >门店管理</a> </li>

</ul>
<div class="row ygrow">
    <div class="col-lg-12">
        <form action="" method="get">
          <input type="hidden" name="c" value="site" />
          <input type="hidden" name="a" value="entry" />
          <input type="hidden" name="m" value="mzhk_sun" />
          <input type="hidden" name="do" value="brand" />
            <div class="input-group">
                <table>
                  <tr>
                    <td><input type="text" name="keywords" class="form-control" value="<?php  echo $keywords;?>" placeholder="请输入商家名称"></td>
                    <td>
                      <span class="input-group-btn">
                        <input type="submit" class="btn btn-default" name="submit" value="查找"/>
                      </span>
                    </td>
                  </tr>
                </table>
            </div>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </form>
    </div><!-- /.col-lg-6 -->
</div>
<div class="main">

    <div class="panel panel-default">
        <div class="panel-heading">
            门店审核管理
        </div>
        <div class="panel-body">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                    <tr class="yg5_tr1">
                        <td class="store_td1 col-md-1" style="text-align: center;">
                          <input type="checkbox" class="allcheck" />
                          <span class="store_inp">全选</span>
                        </td>
                        <td class="store_td1 col-md-1">id</td>
                        <td class="col-md-1">门店名称</td>
                        <td class="col-md-1">总金额</td>
                        <td class="col-md-1">可提现金额</td>
                        <td class="col-md-1">冻结金额</td>
                        <!-- <td class="col-md-1">门店类型</td>
                        <td class="col-md-1">门店特色菜</td>
                        <td class="col-md-1">门店均价</td> -->
                        <td class="col-md-1">入驻</td>
                        <td class="col-md-1">联系方式</td>
                        <td class="col-md-2">门店地址</td>
                        <td class="col-md-1">状态</td>
                        <td class="col-md-1" >操作</td>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                    <tr class="yg5_tr2">
                        <td>
                          <input type="checkbox" name="test" value="<?php  echo $item['gid'];?>">
                        </td>
                        <td><?php  echo $item['bid'];?></td>
                        <td><?php  echo $item['bname'];?></td>
                        <td><?php  echo $item['totalamount'];?></td>
                        <td><?php  echo sprintf("%.2f", ($item['totalamount']-$item['frozenamount']));?></td>
                        <td><?php  echo $item['frozenamount'];?></td>
                        <!-- <td><?php  echo $item['type'];?></td>
                        <td><?php  echo $item['feature'];?></td>
                        <td><?php  echo $item['price'];?></td> -->
                        <td>
                          <?php  if($item['lt_day']) { ?>
                            入驻天数：<?php  echo $item['lt_day'];?>天<br>
                            付款时间：<br><?php  if($item['paytime']) { ?><?php  echo $item['paytime'];?><?php  } else { ?>无<font style="color: #f00">(未支付)</font><?php  } ?>
                          <?php  } ?>
                        </td>
                        <td><?php  echo $item['phone'];?></td>
                        <td><?php  echo $item['address'];?></td>

                        <?php  if($item['status']==1) { ?>
                        <td>
                            <span class="label storered">待审核</span>
                        </td >
                        <?php  } else if($item['status']==2) { ?>
                        <td >
                            <span class="label storeblue">已通过</span>
                        </td>
                        <?php  } else if($item['status']==3) { ?>
                        <td>
                           <span class="label storegrey">已拒绝</span>
                       </td>
                       <?php  } ?>
                       <td>
                           <?php  if($item['status']==1) { ?>
                           <a href="<?php  echo $this->createWebUrl('brand',array('op'=>'tg','bid'=>$item['bid']));?>"><button class="btn storeblue btn-xs">通过</button></a>
                           <a href="<?php  echo $this->createWebUrl('brand',array('op'=>'jj','bid'=>$item['bid']));?>"><button class="btn storegrey btn-xs">拒绝</button></a>
                           <?php  } ?>
                           <?php  if($item['status']==3) { ?>
                           <a href="<?php  echo $this->createWebUrl('brand',array('op'=>'tg','bid'=>$item['bid']));?>"><button class="btn storeblue btn-xs">开启</button></a>
                           <?php  } ?>
                           <?php  if($item['status']==2) { ?>
                           <a href="<?php  echo $this->createWebUrl('brand',array('op'=>'jj','bid'=>$item['bid']));?>"><button class="btn storegrey btn-xs">关闭</button></a>
                           <?php  } ?>
                           <?php  if($item['istop']==0) { ?>
                            <a href="<?php  echo $this->createWebUrl('brand',array('op'=>'top','bid'=>$item['bid'],'istop'=>1));?>"><button class="btn storeblue btn-xs">置顶</button></a>
                           <?php  } else { ?>
                            <a href="<?php  echo $this->createWebUrl('brand',array('op'=>'top','bid'=>$item['bid'],'istop'=>0));?>"><button class="btn storeblue btn-xs">取消置顶</button></a>
                           <?php  } ?>

                           <a href="<?php  echo $this->createWebUrl('brandadd',array('bid'=>$item['bid']));?>" class="storespan btn btn-xs">
                                <span class="fa fa-pencil"></span>
                                <span class="bianji">编辑
                                    <span class="arrowdown"></span>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['bid'];?>">
                                <span class="fa fa-trash-o"></span>
                                <span class="bianji">删除
                                    <span class="arrowdown"></span>
                                </span>
                            </a>

                            <!-- <a href="<?php  echo $this->createWebUrl('goodsinfo',array('id'=>$item['id']));?>"><button class="btn btn-success btn-xs">查看</button></a>
                           <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">删</button> -->
                       </td>

                   </tr>
                   <div class="modal fade" id="myModal<?php  echo $item['bid'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                        </div>
                        <div class="modal-body" style="font-size: 20px">
                            确定删除么？
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a href="<?php  echo $this->createWebUrl('brand', array('op' => 'delete', 'bid' => $item['bid']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  } } ?>
            <?php  if(empty($list)) { ?>
            <tr class="yg5_tr2">
                <td colspan="10">
                      暂无品牌信息
                  </td>
            </tr>
          <?php  } ?>
      </table>
  </div>
</div>
</div>
</div>
<div class="text-right we7-margin-top">
   <?php  echo $pager;?>
</div>
<script type="text/javascript">
    $(function(){
        $("#frame-1").show();
        $("#yframe-1").addClass("wyactive");

    })
</script>