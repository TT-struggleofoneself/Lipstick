<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

if($_GPC['op']=='edituser'){
	$viplist = pdo_getall('mzhk_sun_vip',array('uniacid'=>$_W['uniacid']));
	$info = pdo_get('mzhk_sun_user',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
	include $this->template('web/user_add');
	exit;
}elseif($_GPC['op']=='updateuser'){
	$id=intval($_GPC['id']);
    $data['viptype']=$_GPC['viptype'];
    $data['endtime']=strtotime($_GPC['endtime']);
    $data['telphone']=$_GPC['telphone'];
    $data['addtime']=time();
    $res = pdo_update('mzhk_sun_user', $data, array('id' => $id));
    if($res){
        message('修改成功',$this->createWebUrl('user2'),'success');
    }else{
        message('修改失败','','error');
    }
    
}

$where=" WHERE uniacid=:uniacid ";
$keyword = $_GPC["keywords"];
if(!empty($keyword)){
	$where .=" and name like'%".$keyword."%' ";
}
$isvip = $_GPC["isvip"];
if($isvip>0){
	if($isvip==1){
		$where .=" and viptype >1 ";
	}elseif($isvip==2){
		$where .=" and viptype = 0 ";
	}
}

$data[':uniacid']=$_W['uniacid'];
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select * from " . tablename("mzhk_sun_user") ." {$where} order by id desc ";
$total=pdo_fetchcolumn("select count(id) as wname from " . tablename("mzhk_sun_user") . " {$where} ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);

$pager = pagination($total, $pageindex, $pagesize);

foreach ($list as $k=>$v){
    $data = pdo_get('mzhk_sun_vip',array('uniacid'=>$_W['uniacid'],'id'=>$v['viptype']));
    $list[$k]['title']=$data['title'];
    $list[$k]['endtime']=date('Y-m-d',$v['endtime']);
}

	if($_GPC['op']=='delete'){
		$res4=pdo_delete("mzhk_sun_user",array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		if($res4){
		 message('删除成功！', $this->createWebUrl('user2'), 'success');
		}else{
			  message('删除失败！','','error');
		}
	}
	if($_GPC['op']=='defriend'){
		$res4=pdo_update("mzhk_sun_user",array('state'=>2),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		if($res4){
		 message('拉黑成功！', $this->createWebUrl('user2',array('page'=>$_GPC['page'])), 'success');
		}else{
			  message('拉黑失败！','','error');
		}
	}
	if($_GPC['op']=='relieve'){
		$res4=pdo_update("mzhk_sun_user",array('state'=>1),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		if($res4){
		 message('取消成功！', $this->createWebUrl('user2',array('page'=>$_GPC['page'])), 'success');
		}else{
			  message('取消失败！','','error');
		}
	}

include $this->template('web/user2');