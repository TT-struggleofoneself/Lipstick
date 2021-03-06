<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();


$info=pdo_get('mzhk_sun_goods',array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
$info["lotterytime"] = $info["lotterytime"]>0?date("Y-m-d H:i:s",$info["lotterytime"]):"";
$ship_type = $info["ship_type"]?explode(",",$info["ship_type"]):array();
$brand=pdo_getall('mzhk_sun_brand',array('uniacid'=>$_W['uniacid']));
if($info['zs_imgs']){
    if(strpos($info['zs_imgs'],',')){
        $zs_imgs= explode(',',$info['zs_imgs']);
    }else{
        $zs_imgs=array(
            0=>$info['zs_imgs']
        );
    }
}
if($info['lb_imgs']){
    if(strpos($info['lb_imgs'],',')){
        $lb_imgs= explode(',',$info['lb_imgs']);
    }else{
        $lb_imgs=array(
            0=>$info['lb_imgs']
        );
    }
}

if($info['expirationtime']>0){
    $info['expirationtime'] = date("Y-m-d H:i:s",$info['expirationtime']);
}else{
    $info['expirationtime'] = "";
}

if(checksubmit('submit')){
    
    if($_GPC['goods_name']==null) {
        message('请您填写商品名称', '', 'error');
    }elseif($_GPC['pic']==null){
        message('请您写上传图片','','error');
    }elseif($_GPC['content']==null){
        message('详情不能为空','','error');
    } elseif($_GPC['bid']==null){
        message('门店不能为空','','error');
    }elseif($_GPC['num']==null){
        message('库存不能为零','','error');
    }elseif($_GPC['astime']>=$_GPC['antime']){
        message('活动开始的时间必须比活动结束的时间要早','','error');
    }elseif(empty($_GPC['bid'])){
        message('请选择门店','','error');
    }

    
    if($_GPC['lid']==3){
        if($_GPC['ptprice']==null){
            message('请您填写商品拼团价格','','error');
        }elseif($_GPC['shopprice']==null){
            message('请您填写商品价格','','error');
        }
    }

    
    if($_GPC['lb_imgs']){
        $data['lb_imgs']=implode(",",$_GPC['lb_imgs']);
    }else{
        $data['lb_imgs']='';
    }

    
    $brand = $_GPC['bid'];
    $brandarr = array();
    if(!empty($brand)){
        $brandarr = explode("$$$",$brand);
    }
    $data['bid'] = $brandarr[0];
    $data['bname'] = $brandarr[1];

    $data['is_vip'] = $_GPC["is_vip"];
    if($_GPC["ship_type"]){
        $data['ship_type'] = implode(",",$_GPC["ship_type"]);
    }else{
        $data['ship_type'] = "1";
    }

    $data['ship_delivery_fee'] = $_GPC["ship_delivery_fee"];
    $data['ship_delivery_time'] = $_GPC["ship_delivery_time"];
    $data['ship_delivery_way'] = $_GPC["ship_delivery_way"];
    $data['ship_express_fee'] = $_GPC["ship_express_fee"];

    $data['sort'] = intval($_GPC["sort"]);

    $data['qgprice'] = $_GPC['qgprice'];
    $data['kjprice'] = $_GPC['kjprice'];
    $data['ptprice'] = $_GPC['ptprice'];
    $data['ptnum'] = $_GPC['ptnum'];
    $data['shopprice']=$_GPC['shopprice'];
    $data['uniacid']=$_W['uniacid'];
    $data['gname']=$_GPC['goods_name'];
    $data['content']=html_entity_decode($_GPC['content']);
    $data['lid']=6;
    $data['status']=2;
    $data['tid']=$_GPC['tid'];
    $data['selftime']=date('Y-m-d H:i:s', time());
    $data['probably']=$_GPC['survey'];
    $data['pic'] = $_GPC['pic'];
    
    $data['num'] = $_GPC['num'];
    $data['astime'] = $_GPC['astime'];
    $data['antime'] = $_GPC['antime'];

    $data['lotterytime'] = strtotime($_GPC['lotterytime']);
    $data['winway'] = intval($_GPC['winway']);
    $data['lotterytype'] = intval($_GPC['lotterytype']);
    $data['lotterynum'] = intval($_GPC['lotterynum']);

    $data['index_img'] = $_GPC['index_img'];

    if($_GPC['biaoti']==''){
        $data['biaoti']="我正在参加免单";
    }else{
        $data['biaoti'] = $_GPC['biaoti'];
    }
    $data['expirationtime'] = strtotime($_GPC['expirationtime']);

    if(empty($_GPC['gid'])){
        $res = pdo_insert('mzhk_sun_goods', $data,array('uniacid'=>$_W['uniacid']));

        if($res){
            message('添加成功',$this->createWebUrl('hylist',array()),'success');
        }else{
            message('添加失败','','error');
        }
    }else{

        $res = pdo_update('mzhk_sun_goods', $data, array('gid' => $_GPC['id'],'uniacid'=>$_W['uniacid']));
    }
    if($res){
        message('修改成功',$this->createWebUrl('hylist',array()),'success');
    }else{
        message('修改失败','','error');
    }
}
include $this->template('web/addhylist');