<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info = pdo_get('mzhk_sun_coupon', array('id' => $_GPC['id']));
$brand=pdo_getall('mzhk_sun_brand',array('uniacid'=>$_W['uniacid']));
$val = json_decode($info['val'],true);

if (checksubmit('submit')) {
    if(intval($_GPC['bid'])==0){
        message('请选择门店！');
    }

    $data['title'] = $_GPC['title'];
    $data['type'] =3;
    $data['astime'] = $_GPC['astime'];
    $data['antime'] = $_GPC['antime'];
    $data['expiryDate'] = $_GPC['expiryDate'];
    $data['allowance'] = $_GPC['allowance'];
    $data['total'] = $_GPC['total'];
    $data['val'] = $_GPC['val'];
    $data['vab'] = $_GPC['vab'];
    $data['uniacid'] = $_W['uniacid'];
    $data['showIndex'] =$_GPC['showIndex'];
    $data['bid'] = $_GPC['bid'];
    $data['img'] = $_GPC['img'];
    $data['isvip'] = $_GPC['isvip'];
    $data['content'] = html_entity_decode($_GPC['content']);
    if (empty($_GPC['id'])) {
        $res = pdo_insert('mzhk_sun_coupon', $data,array('uniacid'=>$_W['uniacid']));
        if($res){
            message('添加成功！', $this->createWebUrl('mscounp'), 'success');
        }else{
            message('添加失败！');
        }
    } else {
        $res = pdo_update('mzhk_sun_coupon', $data,array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
    }
    if($res){
        message('编辑成功！', $this->createWebUrl('mscounp'), 'success');
    }else{
        message('编辑失败！');
    }
}

include $this->template('web/addmscounp');
