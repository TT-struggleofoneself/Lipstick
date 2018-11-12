<?php

global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$uniacid = $_W['uniacid'];

if($_GPC['op']=='manuallottery'){
    $gid = intval($_GPC["gid"]);
    if($gid==0){
        message('参数错误！！！','','error');
    }
    $where = " WHERE uniacid=".$_W['uniacid']." and gid=".$gid;
    if(!empty($_GPC['keywords'])){
        $keywords=$_GPC['keywords'];
        $where.=" and orderNum LIKE  '%$keywords%'";
    }
    if(!empty($_GPC['telphone'])){
        $telphone=$_GPC['telphone'];
        $where.=" and telNumber LIKE '%$telphone%'";
    }

    $status=$_GPC['status'];
    $pageindex = max(1, intval($_GPC['page']));
    $pagesize=10;
    $type=isset($_GPC['type'])?$_GPC['type']:'all';
    
    if($type=='all'){

    }else{
        $where.= " and status =$status";
    }
    $sql = "select * from ".tablename('mzhk_sun_hyorder')." ".$where;

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

    include $this->template('web/hymanuallottery');
    exit;
}
if($_GPC['op']=='randlottery'){
    $gid = intval($_GPC["gid"]);
    
    $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("num","islottery","gname"));
    if($goods["islottery"]==1){
        message('失败,该商品已经开过奖了！','','error');
    }
    $lotterynum = intval($goods["num"]);
    $total=pdo_fetchcolumn("select count(oid) as wname from ".tablename('mzhk_sun_hyorder')." where uniacid=".$uniacid." and gid=".$gid." ");
    
    if($lotterynum >= $total){
        $ores = pdo_update('mzhk_sun_hyorder',array('islottery'=>1,"time"=>time()),array('gid'=>$gid,'uniacid'=>$_W['uniacid']));
        if($ores){
            $gres = pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'uniacid'=>$_W['uniacid']));
        }
        if($gres){
            
            $lorreryorder = pdo_getall('mzhk_sun_hyorder',array('uniacid'=>$uniacid,'gid'=>$gid,'islottery'=>1),array("openid","gname","bname","addtime","bid"));
            if($lorreryorder){
                $access_token = getaccess_token();
                foreach($lorreryorder as $k => $v){
                    sendtelmessage($access_token,$v['openid'],$v['gname'],$gid,$v['addtime']);
                }
            }
            message('开奖成功！', $this->createWebUrl('hylist',array('op'=>'manuallottery','gid'=>$gid)), 'success');
        }else{
            message('失败,该商品免单无人申请！','','error');
        }
    }else{
        
        $order=pdo_getall("mzhk_sun_hyorder",array('uniacid'=>$uniacid,'gid'=>$gid,"islottery"=>0),array("oid"));
        if($order){
            $order_arr = $order_id_ar = array();
            foreach($order as $key => $val){
                $order_arr[] = $val["oid"];
            }
            $checkorderid = array_rand($order_arr,$lotterynum);
            if(is_array($checkorderid)){
              foreach($checkorderid as $k => $v){
                  $order_id_arr[] = $order_arr[$v];
              }
            }else{
                $order_id_arr = $order_arr[$checkorderid];
            }
            $ores = pdo_update('mzhk_sun_hyorder',array('islottery'=>1,"time"=>time()),array('oid'=>$order_id_arr,'uniacid'=>$_W['uniacid']));
            if($ores){
                $gres = pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'uniacid'=>$_W['uniacid']));
                $res=pdo_update('mzhk_sun_hyorder',array('islottery'=>2,"time"=>time()),array('islottery'=>0,'uniacid'=>$_W['uniacid']));
                
                
                $lorreryorder = pdo_getall('mzhk_sun_hyorder',array('uniacid'=>$uniacid,'gid'=>$gid),array("openid","gname","bname","addtime","bid"));
                if($lorreryorder){
                    $access_token = getaccess_token();
                    foreach($lorreryorder as $k => $v){
                        sendtelmessage($access_token,$v['openid'],$v['gname'],$gid,$v['addtime']);
                    }
                }
            }
            message('开奖成功！', $this->createWebUrl('hylist',array('op'=>'manuallottery','gid'=>$gid)), 'success');
        }else{
            message('失败,该商品免单无人申请！','','error');
        }
    }
}
if($_GPC['op']=='lottery'){
    $order_id = intval($_GPC["id"]);
    $gid = intval($_GPC["gid"]);
    $islottery = intval($_GPC["islottery"]);
    if($islottery==5){
        $res=pdo_update('mzhk_sun_hyorder',array('islottery'=>2),array('islottery'=>0,'uniacid'=>$_W['uniacid']));
        $gres=pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'islottery'=>0,'uniacid'=>$_W['uniacid']));
        
        
        $lorreryorder = pdo_getall('mzhk_sun_hyorder',array('uniacid'=>$uniacid,'gid'=>$gid),array("openid","gname","bname","addtime","bid"));
        if($lorreryorder){
            $access_token = getaccess_token();
            foreach($lorreryorder as $k => $v){
                sendtelmessage($access_token,$v['openid'],$v['gname'],$gid,$v['addtime']);
            }
        }
    }else{
        $res=pdo_update('mzhk_sun_hyorder',array('islottery'=>$islottery),array('oid'=>$order_id,'uniacid'=>$_W['uniacid']));
        $gres=pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'islottery'=>0,'uniacid'=>$_W['uniacid']));
    }
    
    if($res){
        message('成功！', $this->createWebUrl('hylist',array('op'=>'manuallottery','gid'=>$gid)), 'success');
    }else{
        message('失败！','','error');
    }
}

$where=" WHERE   lid=6 and  uniacid=".$_W['uniacid'];
if($_GPC['keywords']){
    $keywords=$_GPC['keywords'];
    $where.=" and gname LIKE  '%$keywords%'";
}
if($_GPC['status']){
    $where.=" and status={$_GPC['status']} ";

}
if(!empty($_GPC['time'])){
    $start=strtotime($_GPC['time']['start']);
    $end=strtotime($_GPC['time']['end']);
    $where.=" and ime >={$start} and time<={$end}";

}
$status=$_GPC['status'];

$type=isset($_GPC['type'])?$_GPC['type']:'all';
$data[':uniacid']=$_W['uniacid'];

$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select * from " . tablename("mzhk_sun_goods") ." ".$where." order by gid desc ";
$total=pdo_fetchcolumn("select count(*) as wname from " . tablename("mzhk_sun_goods") . " " .$where." order by gid desc ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);

if($_GPC['op']=='delete'){

    $res=pdo_delete('mzhk_sun_goods',array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));

    if($res){
        message('删除成功！', $this->createWebUrl('hylist'), 'success');
    }else{
        message('删除失败！','','error');
    }
}
if($_GPC['op']=='open'){
    $res=pdo_update('mzhk_sun_goods',array('isshelf'=>intval($_GPC["isshelf"])),array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('成功！', $this->createWebUrl('hylist'), 'success');
    }else{
        message('失败！','','error');
    }
}
if($_GPC['op']=='tg'){
    $res=pdo_update('mzhk_sun_goods',array('status'=>2),array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('通过成功！', $this->createWebUrl('hylist'), 'success');
    }else{
        message('通过失败！','','error');
    }
}
if($_GPC['op']=='jj'){
    $res=pdo_update('mzhk_sun_goods',array('status'=>3),array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('拒绝成功！', $this->createWebUrl('hylist'), 'success');
    }else{
        message('拒绝失败！','','error');
    }
}
include $this->template('web/hylist');


function getaccess_token(){
    global $_W, $_GPC;
    $res=pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
    $appid=$res['appid'];
    $secret=$res['appsecret'];
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    $data = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($data,true);
    return $data['access_token'];
}

function sendtelmessage($access_token,$openid,$gname,$gid,$addtime){
    global $_W, $_GPC;

    if(empty($openid)){
        return ;
    }
    
    $delres=pdo_delete('mzhk_sun_userformid',array('time <='=>date('Y-m-d', strtotime('-7 days')),'uniacid'=>$_W['uniacid']));
    $delres=pdo_delete('mzhk_sun_userformid',array('form_id like'=>"mock",'uniacid'=>$_W['uniacid']));
    
    $res2=pdo_get('mzhk_sun_sms',array('uniacid'=>$_W['uniacid']));
    $now = date('Y-m-d', strtotime('-7 days'));
    $sql="select id,form_id from " . tablename("mzhk_sun_userformid") . " where openid='".$openid."' and time>='".$now."' order by id asc";
    $res=pdo_fetch($sql);
    if($res){
        $formwork ='{
            "touser": "'.$openid.'",
            "template_id": "'.$res2["tid4"].'",
            "page":"mzhk_sun/pages/index/freedet/freedet?id='.$gid.'",
            "form_id":"'.$res['form_id'].'",
            "data": {
                "keyword1": {
                    "value": "免单活动",
                    "color": "#173177"
                },
                "keyword2": {
                    "value":"'.$gname.'",
                    "color": "#173177"
                },
                "keyword3": {
                    "value": "'.date("Y-m-d H:i:s").'",
                    "color": "#173177"
                },
                "keyword4": {
                    "value": "您参与的免单活动已开奖，点击进入查看",
                    "color": "#173177"
                }
            }   
        }';
        
        
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
        $data = curl_exec($ch);
        curl_close($ch);

        
        $delres=pdo_delete('mzhk_sun_userformid',array('id'=>$res["id"],'uniacid'=>$_W['uniacid']));
    }
}