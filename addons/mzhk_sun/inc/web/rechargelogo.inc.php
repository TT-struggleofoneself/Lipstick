<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$template = "web/rechargelogo";

if($_GPC['op']=='add'){
    $template = "web/rechargelogo";
}elseif($_GPC['op']=='save'){
    $id=intval($_GPC['id']);
    $data['title']=$_GPC['title'];
    $data['money']=$_GPC['money'];
    $data['lessmoney']=$_GPC['lessmoney'];
    $data['sort']=$_GPC['sort'];
    $data['addtime']=time();
    if($id==0){
        $data['uniacid']=$_W['uniacid'];
        $res=pdo_insert('mzhk_sun_rechargelogo',$data);
        if($res){
            message('添加成功',$this->createWebUrl('rechargelogo'),'success');
        }else{
            message('添加失败','','error');
        }
    }else{
        $res = pdo_update('mzhk_sun_rechargelogo', $data, array('id' => $id));
        if($res){
            message('修改成功',$this->createWebUrl('rechargelogo'),'success');
        }else{
            message('修改失败','','error');
        }
    }
}elseif($_GPC['op']=='edit'){
    $id=intval($_GPC['id']);
    $info=pdo_get('mzhk_sun_rechargelogo',array('uniacid'=>$_W['uniacid'],'id'=>$id));

    $template = "web/rechargelogo";
}elseif($_GPC['op']=='delete'){
    $res=pdo_delete('mzhk_sun_rechargelogo',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('删除成功！', $this->createWebUrl('rechargelogo'), 'success');
    }else{
        message('删除失败！','','error');
    }
}elseif($_GPC['op']=='log'){
    $ty = array("直接充值","充值卡充值","购买会员卡","订单付款","订单退款");
    $where=" WHERE uniacid=".$_W['uniacid']." and rtype<2 ";
    $pageindex = max(1, intval($_GPC['page']));
    $pagesize=10;
    $sql="select * from " . tablename("mzhk_sun_rechargelogo") ." ".$where." order by id desc ";
    $total=pdo_fetchcolumn("select count(*) as wname from " . tablename("mzhk_sun_rechargelogo") . " " .$where." order by id desc ",$data);
    $select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
    $list=pdo_fetchall($select_sql,$data);
    foreach($list as $k => $v){
        $user = pdo_get("mzhk_sun_user",array("openid"=>$v["openid"]),array("name","img"));
        $list[$k]["name"] = $user["name"];
        $list[$k]["img"] = $user["img"];
    }
    $pager = pagination($total, $pageindex, $pagesize);

}else{
    $ty = array("直接充值","充值卡充值","购买会员卡","订单付款","订单退款");
    $where=" WHERE uniacid=".$_W['uniacid']." and rtype>1 ";
    $pageindex = max(1, intval($_GPC['page']));
    $pagesize=10;
    $sql="select * from " . tablename("mzhk_sun_rechargelogo") ." ".$where." order by id desc ";
    $total=pdo_fetchcolumn("select count(*) as wname from " . tablename("mzhk_sun_rechargelogo") . " " .$where." order by id desc ",$data);
    $select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
    $list=pdo_fetchall($select_sql,$data);
    foreach($list as $k => $v){
        $user = pdo_get("mzhk_sun_user",array("openid"=>$v["openid"]),array("name","img"));
        $list[$k]["name"] = $user["name"];
        $list[$k]["img"] = $user["img"];
    }
    $pager = pagination($total, $pageindex, $pagesize);

}

include $this->template($template);