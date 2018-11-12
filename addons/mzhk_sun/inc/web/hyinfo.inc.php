<?php

global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

if($_GPC['op']=='sendgoods'){
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    $sincetype = intval($_GPC['sincetype']);
    if($sincetype==3){
        $shipname = $_GPC['shipname'];
        $shipnum = $_GPC['shipnum'];
        if(empty($shipname) || empty($shipnum)){
            message('快递名称或者快递单号不能为空！','','error');
        }
        $sg_data["shipname"] = $shipname;
        $sg_data["shipnum"] = $shipnum;
    }
    $sg_data["status"] = 1;
    $sg_data["shiptime"] = time();

    $res=pdo_update('mzhk_sun_hyorder',$sg_data,array('oid'=>$id));
    if($res){
        message('发货成功！', $this->createWebUrl('hyinfo'), 'success');
    }else{
        message('发货失败！','','error');
    }
}

$where = " WHERE  uniacid=".$_W['uniacid'];
if(!empty($_GPC['keywords'])){
    $keywords=$_GPC['keywords'];
    $where.=" and orderNum LIKE  '%$keywords%'";
}
if(!empty($_GPC['telphone'])){
    $telphone=$_GPC['telphone'];
    $where.=" and telNumber LIKE '%$telphone%'";
}
if(!empty($_GPC['nametype'])){
    $nametype = $_GPC['nametype'];
    $key_name=$_GPC['key_name'];
    if($nametype=='key_goods'){
        $where.=" and gname LIKE '%$key_name%'";
    }elseif($nametype=='key_bname'){
        $where.=" and bname LIKE '%$key_name%'";        
    }elseif($nametype=='key_uname'){
        $where.=" and name LIKE '%$key_name%'";        
    }
}
if(!empty($_GPC['shiptype'])){
    $shiptype=$_GPC['shiptype'];
    if($shiptype=="到店消费"){
        $where.=" and (sincetype LIKE '%$shiptype%' or sincetype LIKE '%上门自提%')";
    }else{
        $where.=" and sincetype LIKE '%$shiptype%'";
    }
}

if(!empty($_GPC['statustype'])){
    $statustype = intval($_GPC['statustype']);
    if($statustype==9){
        $where.=" and status = 0 ";
    }else{
        $where.=" and status = $statustype ";
    }
}

if(!empty($_GPC['lotterytype'])){
    $lotterytype = intval($_GPC['lotterytype']);
    if($lotterytype==9){
        $where.=" and islottery=0 ";
    }else{
        $where.=" and islottery = $lotterytype";
    }
}

if(!empty($_GPC['timetype'])){
    $timetype = $_GPC["timetype"];
    $time_start_end = $_GPC["time_start_end"];
    if($time_start_end){
        $time_start_end_arr = explode(" - ",$time_start_end);
        if($time_start_end_arr){
            $starttime = strtotime($time_start_end_arr[0]);
            $endtime = strtotime($time_start_end_arr[1]);
            if($timetype=="key_addtime"){
                $where.=" and addtime >= {$starttime} and addtime <= {$endtime} ";
            }elseif($timetype=="key_paytime"){
                $where.=" and paytime >= {$starttime} and paytime <= {$endtime} ";
            }elseif($timetype=="key_finishtime"){
                $where.=" and finishtime >= {$starttime} and finishtime <= {$endtime} ";
            }
        }
    }
}

$status=$_GPC['status'];
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$type=isset($_GPC['type'])?$_GPC['type']:'all';

if($type=='all'){

}else{
    $where.= " and status =$status";
}
$islottery = intval($_GPC['islottery']);
if($islottery>0){
    $where.= " and islottery = $islottery ";
}
$sql = "select * from ".tablename('mzhk_sun_hyorder')." ".$where;


if($_GPC['op']=='exportorder'){
    $select_sql =$sql." order by oid asc ";
    $orderlist=pdo_fetchall($select_sql,$data);
    $export_title_str = "id,订单号,地址,电话,名字,金额(元),运费(元),状态,是否退款,配送方式,下单时间,付款时间,发货时间,完成时间,商家名称,商品名称,数量,快递名称,快递单号,备注,是否中奖";
    $export_title = explode(',',$export_title_str);
    $export_list = array(); 
    $status = array("","取消订单","待支付","已支付","已成团","已完成","待收货");
    $refund = array("否","退款申请中","已退款","拒绝退款");
    $lottery = array("未开奖","中奖","未中奖");
    $i=1;
    foreach ($orderlist as $k => $v){
        $export_list[$k]["k"] = $v["oid"];
        $export_list[$k]["ordernum"] = $v["orderNum"]."\t";
        $export_list[$k]["provincename"] = $v["provinceName"].$v["cityName"].$v["countyName"].$v["detailInfo"];
        $export_list[$k]["telnumber"] = $v["telNumber"]."\t";
        $export_list[$k]["name"] = $v["name"];
        $export_list[$k]["money"] = $v["money"];
        $export_list[$k]["deliveryfee"] = $v["deliveryfee"];
        $export_list[$k]["status"] = $status[$v["status"]];
        $export_list[$k]["isrefund"] = $refund[$v["isrefund"]];
        $export_list[$k]["sincetype"] = $v["sincetype"];
        $export_list[$k]["addtime"] = $v["addtime"]?date("Y-m-d H:i:s",$v["addtime"])."\t":" ";
        $export_list[$k]["paytime"] = $v["paytime"]?date("Y-m-d H:i:s",$v["paytime"])."\t":" ";
        $export_list[$k]["shiptime"] = $v["shiptime"]?date("Y-m-d H:i:s",$v["shiptime"])."\t":" ";
        $export_list[$k]["finishtime"] = $v["finishtime"]?date("Y-m-d H:i:s",$v["finishtime"])."\t":" ";
        $export_list[$k]["bname"] = $v["bname"];
        $export_list[$k]["gname"] = $v["gname"];
        $export_list[$k]["num"] = $v["num"];
        $export_list[$k]["shipname"] = $v["shipname"];
        $export_list[$k]["shipnum"] = $v["shipnum"]."\t";
        $export_list[$k]["uremark"] = " ".$v["uremark"];
        $export_list[$k]["islottery"] = $lottery[$v["islottery"]];
        $i++;
    } 
    $exporttitle = "免单订单";

    exportToExcel($exporttitle.'_'.date("YmdHis").'.csv',$export_title,$export_list);
    exit;
}

$select_sql =$sql." order by oid desc LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$lits=pdo_fetchall($select_sql);
foreach ($lits as $key => $value) {
    if($value["bname"]=='' || $value["bname"]==0){
        $brand = pdo_fetch("select b.bname from ".tablename('mzhk_sun_brand')." as b left join ".tablename('mzhk_sun_goods')." as g on g.bid=b.bid where g.gid=".intval($value["gid"]));
        $lits[$key]["bname"] = $brand["bname"];
    }
}
$total=pdo_fetchcolumn("select count(*) as wname from ".tablename('mzhk_sun_hyorder')." ".$where,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='delete'){
    $res=pdo_delete('mzhk_sun_hyorder',array('oid'=>$_GPC['id']));
    if($res){
        message('删除成功！', $this->createWebUrl('hyinfo'), 'success');
    }else{
        message('删除失败！','','error');
    }
}
if($_GPC['op']=='change'){
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    $status = intval($_GPC["status"]);
    
    $order = pdo_get('mzhk_sun_hyorder',array('uniacid'=>$uniacid,'oid'=>$id),array("money","bid","gid","orderNum","status"));
    if($status==2){
        if($order["status"]==2){
            message('该订单已经是完成状态，不需要在完成订单！','','error');
        }
        $res=pdo_update('mzhk_sun_hyorder',array('status'=>$status,"finishtime"=>time()),array('oid'=>$id));
    }else{
        $res=pdo_update('mzhk_sun_hyorder',array('status'=>$status),array('oid'=>$id));
    }

    if($res){
        if($status==2){
            
            $bid = intval($order['bid']);
            if($bid==0){
                $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$order['gid']),array("bid"));
                $bid = intval($goods['bid']);
            }
            
            $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("totalamount","frozenamount","bname"));

            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$order["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = "集卡订单-订单id：".$id.";订单号：".$order["orderNum"]."；";
            $data["addtime"] = time();
            $data["money"] = $order["money"];
            $data["order_id"] = $id;
            $data["uniacid"] = $uniacid;
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);
        }
        message('成功！', $this->createWebUrl('hyinfo'), 'success');
    }else{
        message('失败！','','error');
    }
}


include $this->template('web/hyinfo');