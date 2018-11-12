<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
file_put_contents(IA_ROOT."/addons/mzhk_sun/inc/web/sqcode.php","");
$check_host = 'http://auth.fzh.fun/tocheck.php';
$cb9b = '12';
$yumi ='www.lzywzb.com';
$client_check = $check_host . '?a=client_check&p='.$cb9b.'&u=' . $yumi;
$check_message = $check_host . '?a=check_message&p='.$cb9b.'&u=' . $yumi;
$check_info=tocurl($client_check);
$check_info = trim($check_info, "\xEF\xBB\xBF");
$message = file_get_contents($check_message);

$json_check_info = json_decode($check_info,true);
if($json_check_info["code"]===0){
    $auth = "已授权";
}else{
	$auth = "您的站点还没有授权";
}
if($check_info=='1'){
   $auth = $message;
}elseif($check_info=='2'){
   $auth = $message;
}elseif($check_info=='3'){
   $auth = $message;
}

include $this->template('web/isauth');