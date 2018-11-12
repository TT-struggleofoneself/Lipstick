<?php
global $_GPC, $_W;

$GLOBALS['frames'] = $this->getMainMenu();
$where=" WHERE uniacid=:uniacid ";
$keyword = $_GPC['keyword'];
if($_GPC['keyword']){
    $op=$_GPC['keyword'];
    $where.=" and bname LIKE '%$op%'";
}





$data[':uniacid']=$_W['uniacid'];
if($_GPC["type"]=='s'){
    $status = intval($_GPC['status']);
    if($status!=999){
        $where .= " and status=:status ";
        $data[':status']=$status;
    }
}else{
    $status = 999;
}
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select * from " . tablename("mzhk_sun_withdraw") ." ".$where." order by id desc ";
$total=pdo_fetchcolumn("select count(*) as wname from " . tablename("mzhk_sun_withdraw") . " " .$where." ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);


$widthdraw = array("","微信","支付宝","银行卡");

if($_GPC['op']=='delete'){

    $res=pdo_delete('mzhk_sun_withdraw',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));

    if($res){
        message('删除成功！', $this->createWebUrl('withdraw'), 'success');
    }else{
        message('删除失败！','','error');
    }
}
if($_GPC['op']=='tg'){
    
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    $withdraw = pdo_get('mzhk_sun_withdraw',array('uniacid'=>$uniacid,'id'=>$id));
    
    $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$withdraw["bid"]),array("commission","totalamount","frozenamount","bname"));
    if($withdraw["wd_type"]==1){
        include IA_ROOT . '/addons/mzhk_sun/wxfirmpay.php';
        $appData = pdo_get('mzhk_sun_system', array('uniacid' => $uniacid));
        $mch_appid = $appData['appid'];
        $mchid = $appData['mchid'];
        $key = $appData['wxkey'];
        $openid = $withdraw["openid"];
        $partner_trade_no = $mchid.time().rand(100000,999999);
        $re_user_name = $withdraw["wd_name"];
        $desc = "提现自动打款";
        $amount = $withdraw["realmoney"]*100;
        $apiclient_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_cert'];
        $apiclient_key = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_key'];
        $weixinfirmpay = new WeixinfirmPay($mch_appid, $mchid, $key, $openid,$partner_trade_no,$re_user_name,$desc,$amount,$apiclient_cert,$apiclient_key);
        $return = $weixinfirmpay->pay();
        if($return['result_code']=='SUCCESS'){
            
            
            $data_brand_up["totalamount"] = $brand["totalamount"] - $withdraw["money"];
            $data_brand_up["frozenamount"] = $brand["frozenamount"] - $withdraw["money"];
            pdo_update('mzhk_sun_brand', $data_brand_up, array('bid' => $withdraw["bid"]));
            
            pdo_update('mzhk_sun_withdraw', array("status"=>1), array('id' => $id));
            
            $data = array();
            $data["bid"] = $withdraw["bid"];
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 2;
            $data["mcd_memo"] = "商家提现-提现总金额:".$withdraw["money"]."元；支付佣金:".$withdraw["paycommission"]."元；支付手续费:".$withdraw["ratesmoney"]."元；实际提现金额:".$withdraw["realmoney"]."元";
            $data["addtime"] = time();
            $data["money"] = $withdraw["money"];
            $data["paycommission"] = $withdraw["paycommission"];
            $data["ratesmoney"] = $withdraw["ratesmoney"];
            $data["wd_id"] = $id;
            $data["uniacid"] = $uniacid;
            $data["status"] = 1;
            $res = pdo_insert('mzhk_sun_mercapdetails', $data);
        }else{
            message('付款失败，平台绑定的微信商户号余额不足！','','error');
        }
    }else{
        
        $data_brand_up["totalamount"] = $brand["totalamount"] - $withdraw["money"];
        $data_brand_up["frozenamount"] = $brand["frozenamount"] - $withdraw["money"];
        pdo_update('mzhk_sun_brand', $data_brand_up, array('bid' => $withdraw["bid"]));

        
        pdo_update('mzhk_sun_withdraw', array("status"=>1), array('id' => $id));
        
        $data = array();
        $data["bid"] = $withdraw["bid"];
        $data["bname"] = $withdraw['bname'];
        $data["mcd_type"] = 2;
        $data["mcd_memo"] = "商家提现-提现总金额:".$withdraw["money"]."元；支付佣金:".$withdraw["paycommission"]."元；支付手续费:".$withdraw["ratesmoney"]."元；实际提现金额:".$withdraw["realmoney"]."元";
        $data["addtime"] = time();
        $data["money"] = $withdraw["money"];
        $data["paycommission"] = $withdraw["paycommission"];
        $data["ratesmoney"] = $withdraw["ratesmoney"];
        $data["wd_id"] = $id;
        $data["uniacid"] = $uniacid;
        $data["status"] = 1;
        $res = pdo_insert('mzhk_sun_mercapdetails', $data);
    }

    if($res){
        message('成功！', $this->createWebUrl('withdraw'), 'success');
    }else{
        message('失败！','','error');
    }
}
if($_GPC['op']=='jj'){
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    $withdraw = pdo_get('mzhk_sun_withdraw',array('uniacid'=>$uniacid,'id'=>$id));
    
    $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$withdraw["bid"]),array("commission","totalamount","frozenamount","bname"));
    
    $data_brand_up["frozenamount"] = $brand["frozenamount"] - $withdraw["money"];
    pdo_update('mzhk_sun_brand', $data_brand_up, array('bid' => $withdraw["bid"]));

    $res=pdo_update('mzhk_sun_withdraw',array('status'=>2),array('id'=>$id,'uniacid'=>$uniacid));
    if($res){
        message('拒绝成功！', $this->createWebUrl('withdraw'), 'success');
    }else{
        message('拒绝失败！','','error');
    }
}


include $this->template('web/withdraw');