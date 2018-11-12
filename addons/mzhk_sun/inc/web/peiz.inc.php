<?php
global $_GPC, $_W;


$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
if(checksubmit('submit')){

    $apiclient_cert=$_FILES['apiclient_cert'];
    $apiclient_key=$_FILES['apiclient_key'];
    $apiclient_cert_size=$_FILES['apiclient_cert']['size'];
    $apiclient_key_size=$_FILES['apiclient_key']['size'];
    if($apiclient_cert['name'] || $apiclient_key['name']){
        if($apiclient_cert['type']!='application/octet-stream' ||$apiclient_key['type']!='application/octet-stream'){
            message('文件类型只能为pem格式');
        }
    }
    if($apiclient_cert_size>2*1024*1024 || $apiclient_key_size>2*1024*1024) {
       message('上传文件过大，不得超过2M');
    }

    
    $user_path=IA_ROOT."/addons/mzhk_sun/cert/";

    
    if(is_uploaded_file($_FILES['apiclient_cert']['tmp_name'])) {
        
        $uploaded_file=$_FILES['apiclient_cert']['tmp_name'];

        
        if(!file_exists($user_path)) {
            mkdir($user_path);
        }
        $file_true_name=$_FILES['apiclient_cert']['name'];
        $file_true_name = rtrim($file_true_name,'.pem');
        $file_true_name = $file_true_name . '_' . $_W['uniacid'] . '.pem';
        $apiclient_cert_name = $file_true_name;
        $move_to_file=$user_path.$file_true_name;

        if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {
            
            $data['apiclient_cert']=$apiclient_cert_name;
        } else {
            echo "上传失败";
        }
    } else {
        echo "上传失败";
    }
    
    if(is_uploaded_file($_FILES['apiclient_key']['tmp_name'])) {
        
        $uploaded_file=$_FILES['apiclient_key']['tmp_name'];
        
        
        if(!file_exists($user_path)) {
            mkdir($user_path);
        }
        $file_true_name=$_FILES['apiclient_key']['name'];
        $file_true_name = rtrim($file_true_name,'.pem');
        $file_true_name = $file_true_name . '_' . $_W['uniacid'] . '.pem';
        $apiclient_key_name = $file_true_name;
        $move_to_file=$user_path.$file_true_name;
        
        if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {
            
            $data['apiclient_key']=$apiclient_key_name;
        } else {
            echo "上传失败";
        }
    } else {
        echo "上传失败";
    }

    $data['appid']=trim($_GPC['appid']);
    $data['appsecret']=trim($_GPC['appsecret']);
    $data['wxkey']=trim($_GPC['wxkey']);
    $data['mchid']=trim($_GPC['mchid']);

    if($_GPC['appid']==''){
        message('小程序appid不能为空!','','error');
    }
    if($_GPC['appsecret']==''){
        message('小程序appsecret不能为空!','','error');
    }
    $data['uniacid']=trim($_W['uniacid']);

    if($_GPC['id']==''){                
        $res=pdo_insert('mzhk_sun_system',$data,array('uniacid'=>$_W['uniacid']));
        if($res){
            message('添加成功',$this->createWebUrl('peiz',array()),'success');
        }else{
            message('添加失败','','error');
        }
    }else{
        $res = pdo_update('mzhk_sun_system', $data, array('id' => $_GPC['id'],'uniacid'=>$_W['uniacid']));
        if($res){
            message('编辑成功',$this->createWebUrl('peiz',array()),'success');
        }else{
            message('编辑失败','','error');
        }
    }
}

include $this->template('web/peiz');