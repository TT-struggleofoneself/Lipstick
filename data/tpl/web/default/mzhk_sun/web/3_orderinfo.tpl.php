<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/mzhk_sun/template/public/ygcsslist.css">
<style type="text/css">
  .input-group .form-control{display: inline-block;width: 200px;float: none;}
</style>
<ul class="nav nav-tabs">
  <span class="ygxian"></span>
  <div class="ygdangq">当前位置:</div>
  <li <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('orderinfo',array('type'=>all));?>">全部订单</a></li>
  <li <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('orderinfo',array('type'=>wait,'status'=>2));?>">待支付</a></li>
  <li <?php  if($type=='ok') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('orderinfo',array('type'=>ok,'status'=>3));?>">待确认</a></li>
  <li <?php  if($type=='no') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('orderinfo',array('type'=>no,'status'=>5));?>">已完成</a></li>
</ul>
<div class="row ygrow">
    <div class="col-lg-12">
        <form action="" method="get">
          <input type="hidden" name="c" value="site" />
          <input type="hidden" name="a" value="entry" />
          <input type="hidden" name="m" value="mzhk_sun" />
          <input type="hidden" name="do" value="orderinfo" />
          <input type="hidden" name="op" id="doop" value="" />
            <div class="input-group">
                <table>
                  <tr>
                    <td>
                      <select name="nametype">
                        <option value="">请选择输入内容</option>
                        <option value="key_goods" <?php  if($nametype=='key_goods') { ?>selected<?php  } ?>>商品名称</option>
                        <option value="key_bname" <?php  if($nametype=='key_bname') { ?>selected<?php  } ?>>商家名称</option>
                        <option value="key_uname" <?php  if($nametype=='key_uname') { ?>selected<?php  } ?>>用户名称</option>
                      </select>
                    </td>
                    <td><input type="text" name="key_name" class="form-control" value="<?php  echo $key_name;?>" placeholder="请输入"></td>
                    <td>
                      <select name="shiptype">
                        <option value="">请选择配送方式</option>
                        <option value="到店消费" <?php  if($shiptype=='到店消费') { ?>selected<?php  } ?>>到店消费</option>
                        <option value="送货上门" <?php  if($shiptype=='送货上门') { ?>selected<?php  } ?>>送货上门</option>
                        <option value="快递" <?php  if($shiptype=='快递') { ?>selected<?php  } ?>>快递</option>
                      </select>
                    </td>
                    <td>订单号：<input type="text" name="keywords" class="form-control" value="<?php  echo $keywords;?>" placeholder="请输入订单号"></td>
                    <td>手机号：<input type="text" name="telphone" class="form-control" value="<?php  echo $telphone;?>" placeholder="请输入手机号码"></td>
                  </tr>
                  <tr>
                    <td>
                      <select name="statustype">
                        <option value="">请选择订单状态</option>
                        <option value="1" <?php  if($statustype=='1') { ?>selected<?php  } ?>>取消订单</option>
                        <option value="2" <?php  if($statustype=='2') { ?>selected<?php  } ?>>待付款</option>
                        <option value="3" <?php  if($statustype=='3') { ?>selected<?php  } ?>>待使用</option>
                        <option value="4" <?php  if($statustype=='4') { ?>selected<?php  } ?>>待收货</option>
                        <option value="5" <?php  if($statustype=='5') { ?>selected<?php  } ?>>已完成</option>
                        <option value="91" <?php  if($statustype=='91') { ?>selected<?php  } ?>>退款申请</option>
                        <option value="92" <?php  if($statustype=='92') { ?>selected<?php  } ?>>已退款</option>
                        <option value="93" <?php  if($statustype=='93') { ?>selected<?php  } ?>>拒绝退款</option>
                      </select>
                    </td>
                    <td>
                      <select name="timetype">
                        <option value="">请选择要搜索的时间方式</option>
                        <option value="key_addtime" <?php  if($timetype=='key_addtime') { ?>selected<?php  } ?>>下单时间</option>
                        <option value="key_paytime" <?php  if($timetype=='key_paytime') { ?>selected<?php  } ?>>付款时间</option>
                        <option value="key_finishtime" <?php  if($timetype=='key_finishtime') { ?>selected<?php  } ?>>完成时间</option>
                      </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="time_start_end" value="<?php  echo $time_start_end;?>" id="time_start_end" placeholder="-" style="width: 200px">
                    </td>
                    <td>
                      <span class="input-group-btn">
                        <input type="submit" class="btn btn-default" name="submit" id="searchorder" value="查找"/>
                        <input type="submit" class="btn btn-default" name="submit" id="exportorder" value="导出"/>
                      </span>
                    </td>
                    <td>
                        
                    </td>
                  </tr>
                </table>
            </div>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </form>

        <div class="col-md-4">
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </div>
    </div><!-- /.col-lg-6 -->
</div>
<div class="main">
    <div class="panel panel-default">
        <div class="panel-body ygbtn">
            <!--<div class="btn ygshouqian2" id="allselect">批量删除</div>-->
            <!--<div class="btn ygyouhui2" id="allpass">批量通过</div>-->
            <!--<div class="btn storegrey2" id="allrefuse">批量拒绝</div>-->
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            订单管理
        </div>
        <div class="panel-body" style="padding: 0px 15px;">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                   <tr class="yg5_tr1">
                        <!-- <td class="store_td1 col-md-1" style="text-align: center;">
                          <input type="checkbox" class="allcheck" />
                          <span class="store_inp">全选</span>
                        </td> -->
                       <!--  <td class="store_td1 col-md-1">id</td> -->
                        <td class="store_td1 col-md-1">订单号</td>
                        <!-- <td class="col-md-1">团单号</td> -->
                        <td class="col-md-0">团长/团员</td>
                        <td class="col-md-1">下单时间</td>
                        <td class="col-md-1">完成时间</td>
                        <td class="col-md-1">商品名称</td>
                        <td class="col-md-1">商家名称</td>
                        <td class="col-md-1">订单金额</td>
                        <!-- <td class="col-md-1">配送费</td> -->
                        <td class="col-md-1">用户电话</td>
                        <td class="col-md-1">用户信息</td>
                        <td class="col-md-1">配送方式</td>
                        <td class="col-md-1">订单状态</td>
                        <td class="col-md-2">操作</td>
                    </tr>
                    <?php  if(is_array($lits)) { foreach($lits as $key => $item) { ?>
                    <tr class="yg5_tr2">
                      <!-- <td>
                          <input type="checkbox" name="test" value="<?php  echo $item['oid'];?>">
                        </td> -->
                    <!--  <td><?php  echo $item['id'];?></td> -->
                    <td class="store_td1 col-md-1">
                      <?php  echo $item['groupordernum'];?><br>
                      总团单号：<?php  echo $item['ordernum'];?>
                    </td>
                    <!-- <td><?php  echo $item['ordernum'];?></td> -->
                    <td><?php  if($item['is_lead']==1) { ?><font color="red">团长</font><?php  } else { ?>团员<?php  } ?></td>
                    <td><?php  echo date("Y-m-d H:i:s",$item['addtime'])?></td>
                    <td><?php  if($item['finishtime']>0) { ?><?php  echo date("Y-m-d H:i:s",$item['finishtime'])?><?php  } ?></td>
                      <td><?php  echo $item['gname'];?></td>
                      <td><?php  echo $item['bname'];?></td>
                      <td><?php  echo $item['money'];?>元</td>
                      <!-- <td><?php  echo $item['deliveryfee'];?>元</td> -->
                      <td><?php  echo $item['telnumber'];?></td>
                      <td> 
                        收货人：<?php  echo $item['name'];?><br>
                        收货地址：<?php  echo $item['provincename'];?><?php  echo $item['cityname'];?><?php  echo $item['countyname'];?><?php  echo $item['detailinfo'];?>
                      </td>
                      <td><?php  echo $item['sincetype'];?></td>
                      <!--   <td><?php  if($item['top']==1) { ?>是<?php  } else { ?>否<?php  } ?></td> -->
                     <td>
                     <?php  if($item['status']== 2) { ?>
                     <span class="label storeblue">待支付</span>
                     <?php  } else if($item['status']== 3 ) { ?>
                     <span class="label storegrey" style="background: #9a4e67">已支付</span>
                     <?php  } else if($item['status']== 4 ) { ?>
                        <span class="label storegrey" style="background: #b32e2e">已成团</span>
                        <?php  if($item['isrefund']== 1) { ?>
                            <span class="label storegrey" style="background: #d88504">退款中</span>
                        <?php  } else if($item['isrefund']== 2) { ?>
                            <span class="label storegrey" style="background: #d88504">已退款</span>
                        <?php  } else if($item['isrefund']== 3) { ?>
                            <span class="label storegrey" style="background: #d88504">拒绝退款</span>
                        <?php  } ?>
                     <?php  } else if($item['status']== 5 ) { ?>
                     <span class="label storegrey" style="background: #4f9a0b">已完成</span>
                     <?php  } else if($item['status']== 6 ) { ?>
                     <span class="label storegrey" style="background: #0c6bce">待收货</span>
                     <?php  } else if($item['status']==1 ) { ?>
                     <span class="label storegrey">已取消</span>
                    <?php  } ?>
                       <td>
                          <?php  if(($item['isrefund']==1 || $item['isrefund']==3) && $item['status']== 4) { ?>
                            <a href="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'refund', 'id' => $item['id']))?>">
                              <span class="label storegrey" style="background: #d88504">确认退款</span>
                            </a>
                            <a href="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'refund', 'id' => $item['id'], 'isrefund' => 3))?>">
                              <span class="label storegrey" style="background: #d88504">拒绝退款</span>
                            </a>
                          <?php  } ?>
                          <?php  if($item['status']==4 && ($item['sincetype']=="送货上门" || $item['sincetype']=="快递")) { ?>
                              <?php  if($item['sincetype']=="快递") { ?>
                                <a href="" data-toggle="modal" data-target="#myModalSend<?php  echo $item['id'];?>">
                                    <span class="label storegrey">发货</span>
                                </a>
                              <?php  } else { ?>
                                <a href="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'sendgoods', 'id' => $item['id']))?>">
                                    <span class="label storegrey">发货</span>
                                </a>
                              <?php  } ?>
                          <?php  } ?>
                          <a href="<?php  echo $this->createWebUrl('orderview', array('seetype' => 1, 'id' => $item['id']))?>">
                              <span class="label storegrey">查看订单</span>
                          </a>
                          <?php  if($item['status']==3) { ?>
                              <a href="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'tobegroup', 'orderid' => $item['order_id']))?>">
                                  <span class="label storegrey" style="background: #9a4e67">点击成团</span>
                              </a>
                          <?php  } ?>
                          <?php  if($item['status']>=4 && $item['status']!=5 && $item['isrefund']!= 2) { ?>
                              <a href="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'send', 'id' => $item['id']))?>">
                                  <span class="label storegrey" style="background: #4f9a0b">完成订单</span>
                              </a>
                          <?php  } ?>
                          <a href="" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">
                              <span class="fa fa-trash-o"></span>
                              <span class="bianji">删除
                                  <span class="arrowdown"></span>
                              </span>
                          </a>
                       </td>
                   </tr>
                   <!--发货-->
                   <div class="modal fade" id="myModalSend<?php  echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示：发快递需要填写发送的快递和快递单号</h4>
                                </div>
                                <form action="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'sendgoods', 'id' => $item['id'], 'sincetype' => 3))?>" method="post">
                                  <div class="modal-body ygsearch" style="font-size: 14px;padding: 15px 30px;">
                                      <div>快递名称：<input type="text" name="shipname" style="border: #a9a9a9 1px solid;font-size: 14px;padding:5px; " placeholder="请输入快递名称"></div>
                                      <div style="padding-top: 10px;">快递单号：<input type="text" style="border: #a9a9a9 1px solid;font-size: 14px;padding:5px;" name="shipnum" placeholder="请输入快递单号"></div>
                                      <div class="searchname" style="margin-top: 8px;"></div>
                                  </div>
                                  <div class="modal-footer">
                                      <input type="submit" name="submit" value="发货" class="btn col-lg-3" style="color: white;background-color: #444444;margin-left: 100px;"/>
                                      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--删除-->
                   <div class="modal fade" id="myModal<?php  echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <a href="<?php  echo $this->createWebUrl('orderinfo', array('op' => 'delete', 'id' => $item['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  } } ?>
            <?php  if(empty($lits)) { ?>
            <tr class="yg5_tr2">
            <td colspan="11">
                  暂无订单信息
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

        layui.use('laydate', function () {
            var laydate = layui.laydate;
            laydate.render({
              elem: '#time_start_end',
              range: true
            });
        });

        $(".allcheck").on('click',function(){
            var checked = $(this).get(0).checked;
            $("input[type=checkbox]").prop("checked",checked);
        });

        $("#exportorder").on('click',function(){
            $("#doop").val("exportorder");
        })
        $("#searchorder").on('click',function(){
            $("#doop").val("");
        })

        $("#frame-2").show();
        $("#yframe-2").addClass("wyactive");
    })
</script>