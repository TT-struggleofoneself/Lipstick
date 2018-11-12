<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$uniacid = $_W['uniacid'];

$where=" WHERE uniacid=:uniacid ";
$keyword = $_GPC['keyword'];
if($_GPC['keyword']){
    $op=$_GPC['keyword'];
    $where.=" and bname LIKE  '%$op%'";
}

$type=isset($_GPC['type'])?$_GPC['type']:'all';

if($_GPC["type"]=='s'){
    $status = intval($_GPC['status']);
    if($status!=999){
        $where .= " and mcd_type=:status ";
        $data[':status']=$status;
    }
}else{
    $status = 999;
}

$data[':uniacid']=$_W['uniacid'];

$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select * from " . tablename("mzhk_sun_mercapdetails") ." ".$where." order by id desc ";
$total=pdo_fetchcolumn("select count(*) as wname from " . tablename("mzhk_sun_mercapdetails") . " " .$where." ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);


$widthdraw = array("","订单收入","提现","线下付款");

if($_GPC['op']=='delete'){

    $res=pdo_delete('mzhk_sun_mercapdetails',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));

    if($res){
        message('删除成功！', $this->createWebUrl('mercapdetails'), 'success');
    }else{
        message('删除失败！','','error');
    }
}
if($_GPC['op']=='paytomch'){
    $id = intval($_GPC['id']);

    $mercapdetails = pdo_get('mzhk_sun_mercapdetails', array('id' => $id,'uniacid' => $uniacid,'status' => 2));
    if($mercapdetails){
        $bid = $mercapdetails["bid"];
        $price = $mercapdetails["money"];
        $mer_id = $mercapdetails["id"];

        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("memdiscount","bname","bind_openid"));

        include IA_ROOT . '/addons/mzhk_sun/wxfirmpay.php';
        $appData = pdo_get('mzhk_sun_system', array('uniacid' => $uniacid));
        $mch_appid = $appData['appid'];
        $mchid = $appData['mchid'];
        $key = $appData['wxkey'];
        $openid = $brand["bind_openid"];
        $partner_trade_no = $mchid.time().rand(100000,999999);
        $re_user_name = $brand["bname"];
        $desc = "线下付款-自动打款";
        $offlinefee = 0;
        
        if($appData["offlinefee"]>0){
            $offlinefee = $price*$appData["offlinefee"]/100;
            $amount = sprintf("%.2f", ($price - $offlinefee));
            $amount = $amount*100;
        }else{
            $amount = $price*100;
        }

        $apiclient_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_cert'];
        $apiclient_key = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_key'];

        $weixinfirmpay = new WeixinfirmPay($mch_appid, $mchid, $key, $openid,$partner_trade_no,$re_user_name,$desc,$amount,$apiclient_cert,$apiclient_key);
        $return = $weixinfirmpay->pay();

        if($return['result_code']=='SUCCESS'){
            
            $data = array();
            $data["status"] = 1;
            $data["mcd_memo"] = "线下付款-直接打款给商家-支付金额:".$price."元,商家实收".($amount/100)."元，收取手续费".$offlinefee."元";
            pdo_update('mzhk_sun_mercapdetails', $data, array('id' => $mer_id));
            message('付款成功！','','error');
        }else{
            $data = array();
            $data["status"] = 2;
            $data["mcd_memo"] = "线下付款-直接打款给商家-支付金额:".$price."元,用户付款到平台微信商户号成功，由于绑定微信商户号问题导致无法付款给商家；错误代码".$return['result_code']."-错误信息:".$return['return_msg'].";（".$return['err_code_des']."）";
            pdo_update('mzhk_sun_mercapdetails', $data, array('id' => $mer_id));
            message("失败，错误代码".$return['result_code']."-错误信息:".$return['return_msg'].";（".$return['err_code_des']."）",'','error');
        }
    } else{
        message('付款失败！','','error');
    }
}
include $this->template('web/mercapdetails');