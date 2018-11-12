<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

if($_GPC['op']=='search'){
    $name=$_GPC['name'];
    $where=" WHERE uniacid=".$_W['uniacid'];
    $sql="select openid,name as uname from " . tablename("mzhk_sun_user") ." ".$where." and name like'%".$name."%' ";
    $list=pdo_fetchall($sql);
    echo json_encode($list);
    exit();
}


$sclist=pdo_getall('mzhk_sun_storecate',array('uniacid'=>$_W['uniacid']));


$info=pdo_get('mzhk_sun_brand',array('bid'=>$_GPC['bid'],'uniacid'=>$_W['uniacid']));


$userlist=pdo_get('mzhk_sun_user',array('uniacid'=>$_W['uniacid'],'openid'=>$info['bind_openid']),array("name"));
$info["name"] = $userlist["name"];


$sflist=pdo_getall('mzhk_sun_storefacility',array('uniacid'=>$_W['uniacid']));
$facility = $info["facility"]?explode(",",$info["facility"]):array();
foreach($sflist as $k=> $v){
    if(in_array($v["id"], $facility)){
        $sflist[$k]["check"] = 1;
    }
}

if($info['logo']){
    if(strpos($info['logo'],',')){
        $logo= explode(',',$info['logo']);
    }else{
        $logo=array(
            0=>$info['logo']
        );
    }
}

if(checksubmit('submit')){
  

    if($_GPC['bname']==null) {
        message('请您填写品牌名称', '', 'error');
    }elseif($_GPC['content']==null){
        message('请您填写品牌详情','','error');
    }elseif($_GPC['logo']==null){
        message('请您写上传图片','','error');die;
    }elseif($_GPC['address']==null){
        message('请您写商家地址','','error');die;
    }elseif($_GPC['phone']==null){
        message('为了方便客户找到店铺，请您写联系方式','','error');die;
    }elseif($_GPC['loginname']=='' || $_GPC['loginpassword']==''){
        message('登陆账号和密码不能为空','','error');die;
    }elseif($_GPC['store_id']==''){
        message('请选择分类','','error');die;
    }elseif($_GPC['coordinates']==''){
        message('请定位地图，需要获取经纬度','','error');die;
    }

    $store = $_GPC['store_id'];
    $storearr = array();
    if(!empty($store)){
        $storearr = explode("$$$",$store);
    }
    $data['store_id'] = $storearr[0];
    $data['store_name'] = $storearr[1];

    $data['starttime']=$_GPC['starttime'];
    $data['endtime']=$_GPC['endtime'];

    $data['facility']=implode(",",$_GPC['facility']);

    
    $data['deliveryfee']=$_GPC['deliveryfee'];
    $data['deliverytime']=$_GPC['deliverytime'];
    $data['deliveryaway']=$_GPC['deliveryaway'];

    $data['bind_openid'] = $_GPC['bind_openid'];
    $data['in_openid'] = $_GPC['bind_openid'];
    $data['loginname'] = $_GPC['loginname'];
    $data['loginpassword'] = $_GPC['loginpassword'];

    $coordinates = trim($_GPC['coordinates']);
    
    $coordinatesarr = explode(",",$coordinates);
    
    $data['coordinates'] = trim($coordinates);
    $data['latitude'] = $coordinatesarr[0];
    $data['longitude'] = $coordinatesarr[1];

    $data['sort'] = intval($_GPC['sort']);
    
    
    

    
    $loginname_old = trim($_GPC['loginname_old']);
    if(empty($loginname_old)){
        $checkinfo=pdo_get('mzhk_sun_brand',array('loginname'=>$_GPC['loginname'],'uniacid'=>$_W['uniacid']));
        if($checkinfo){
            message('登陆账号已经存在，请重新输入','','error');die;
        }
    }else{
        if($_GPC['loginname'] != $loginname_old){
            $checkinfo=pdo_get('mzhk_sun_brand',array('loginname'=>$_GPC['loginname'],'uniacid'=>$_W['uniacid']));
            if($checkinfo){
                message('登陆账号已经存在，请重新输入','','error');die;
            }
        }
    }

    $data['memdiscount'] = $_GPC['memdiscount'];
    $data['commission'] = intval($_GPC['commission']);

    $data['uname']=$_GPC['uname'];
    $data['uniacid']=$_W['uniacid'];
	$data['bname']=$_GPC['bname'];
    $data['phone']=$_GPC['phone'];
    $data['address']=$_GPC['address'];
	$data['status']=2;
    $data['img']=$_GPC['img'];
    $data['price']=$_GPC['price'];
    $data['feature']=$_GPC['feature'];
    $data['type']=$_GPC['type'];
	$data['content']=html_entity_decode($_GPC['content']);
    $data['logo']=implode(",",$_GPC['logo']);

	if(empty($_GPC['bid'])){
        $res = pdo_insert('mzhk_sun_brand', $data,array('uniacid'=>$_W['uniacid']));

        if($res){
            message('添加成功',$this->createWebUrl('brand',array()),'success');
        }else{
            message('添加失败','','error');
        }
    }else{

        $res = pdo_update('mzhk_sun_brand', $data, array('bid' => $_GPC['bid'],'uniacid'=>$_W['uniacid']));
    }
	if($res){
		message('修改成功',$this->createWebUrl('brand',array()),'success');
	}else{
		message('修改失败','','error');
	}
}

include $this->template('web/brandadd');