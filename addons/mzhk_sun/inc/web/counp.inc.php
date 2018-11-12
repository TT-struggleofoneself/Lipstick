<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

$info = pdo_getall('mzhk_sun_coupon',array('uniacid'=>$_W['uniacid']));

global $_W, $_GPC;

if($_GPC['op']=='delete'){
    if($_W['ispost']){
        $res=pdo_delete('mzhk_sun_coupon',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
        if($res){
            message('操作成功',$this->createWebUrl('counp',array()),'success');
        }else{
            message('操作失败','','error');
        }
    }
}
if($_GPC['op']=='change'){
    $res=pdo_update('mzhk_sun_coupon',array('state'=>$_GPC['state']),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('操作成功',$this->createWebUrl('counp',array()),'success');
    }else{
        message('操作失败','','error');
    }
}

include $this->template('web/counp');