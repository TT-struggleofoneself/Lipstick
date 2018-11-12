<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

$where=" WHERE b.uniacid=".$_W['uniacid'];

if(!empty($_GPC['keywords'])){
    $keywords=$_GPC['keywords'];
    $where.=" and b.bname LIKE '%$keywords%'";
}

$type=isset($_GPC['type'])?$_GPC['type']:'all';
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="SELECT * FROM ".tablename('mzhk_sun_brand')." as b left join ".tablename('mzhk_sun_storelimit')." as s on b.lt_id=s.id ".$where." order by b.bid desc ";
$total=pdo_fetchcolumn("SELECT  count(*) as wname FROM ".tablename('mzhk_sun_brand')." as b left join ".tablename('mzhk_sun_storelimit')." as s on b.lt_id=s.id ".$where." order by b.bid desc ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);

foreach($list as $k=> $v){
    $list[$k]["paytime"] = $v["paytime"]>0?date("Y-m-d",$v["paytime"]):'';
}
$pager = pagination($total, $pageindex, $pagesize);

if($_GPC['op']=='delete'){

    $res=pdo_delete('mzhk_sun_brand',array('bid'=>$_GPC['bid'],'uniacid'=>$_W['uniacid']));

    if($res){
        message('删除成功！', $this->createWebUrl('brand'), 'success');
    }else{
        message('删除失败！','','error');
    }
}
if($_GPC['op']=='tg'){
    $res=pdo_update('mzhk_sun_brand',array('status'=>2),array('bid'=>$_GPC['bid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('通过成功！', $this->createWebUrl('brand'), 'success');
    }else{
        message('通过失败！','','error');
    }
}
if($_GPC['op']=='top'){
    $istop = intval($_GPC["istop"]);
    $res=pdo_update('mzhk_sun_brand',array('istop'=>$istop),array('bid'=>$_GPC['bid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('置顶成功！', $this->createWebUrl('brand'), 'success');
    }else{
        message('置顶失败！','','error');
    }
}
if($_GPC['op']=='jj'){
    $res=pdo_update('mzhk_sun_brand',array('status'=>3),array('bid'=>$_GPC['bid'],'uniacid'=>$_W['uniacid']));
    if($res){
        
        
        message('成功！', $this->createWebUrl('brand'), 'success');
    }else{
        message('失败！','','error');
    }
}

include $this->template('web/brand');