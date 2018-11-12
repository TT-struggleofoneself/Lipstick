<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('mzhk_sun_goods',array('uniacid'=>$_W['uniacid']));
if(checksubmit('submit')){
    $data['is_hyopen']=$_GPC['is_hyopen'];

    $res = pdo_update('mzhk_sun_goods', $data);
    if($res){
        message('编辑成功',$this->createWebUrl('hyopen',array()),'success');
    }else{
        message('编辑失败','','error');
    }

}
include $this->template('web/hyopen');