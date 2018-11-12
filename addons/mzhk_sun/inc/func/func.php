<?php
defined('IN_IA') or exit ('Access Denied');
global $_W, $_GPC;


function SearchProductLikename($keyword="",$tid=0){
    global $_W;
    $tid=$tid;
    $name=$keyword;
    $where=" WHERE uniacid=".$_W['uniacid'];
    if($tid==6){
        $sql="select bid as gid,bname as gname from " . tablename("mzhk_sun_brand") ." ".$where." and bname like'%".$name."%' ";
    }elseif($tid==7){
        $sql="select gid,gname from " . tablename("mzhk_sun_goods") ." ".$where." and lid=2 and gname like'%".$name."%' ";
    }elseif($tid==8){
        $sql="select gid,gname from " . tablename("mzhk_sun_goods") ." ".$where." and lid=4 and gname like'%".$name."%' ";
    }elseif($tid==9){
        $sql="select gid,gname from " . tablename("mzhk_sun_goods") ." ".$where." and lid=5 and gname like'%".$name."%' ";
    }elseif($tid==10){
        $sql="select gid,gname from " . tablename("mzhk_sun_goods") ." ".$where." and lid=3 and gname like'%".$name."%' ";
    }elseif($tid==11){
        $sql="select id as gid,title as gname from " . tablename("mzhk_sun_coupon") ." ".$where." and isvip=1 and is_counp=1 and title like'%".$name."%' ";
    }elseif($tid==12){
        $sql="select id as gid,title as gname from " . tablename("mzhk_sun_wxappjump") ." ".$where." and title like'%".$name."%' ";
    }elseif($tid==13){
        $sql="select gid,gname from " . tablename("mzhk_sun_goods") ." ".$where." and lid=6 and gname like'%".$name."%' ";
    }elseif($tid==20){
        $sql="select id as gid,title as gname from " . tablename("mzhk_sun_specialtopic") ." ".$where." and title like'%".$name."%' ";
    }
    $list=pdo_fetchall($sql);
    return $list;
}

function GetPositon(){
    $typearr = array(
        "1"=>"不需要链接",
        "15"=>"首页",
        "2"=>"砍价",
        "3"=>"集卡",
        "4"=>"抢购",
        "5"=>"拼团",
        "14"=>"免单",
        "18"=>"我的",
        "19"=>"专题",
        "21"=>"圈子",
        "6"=>"店铺",
        "24"=>"购买会员卡",
        "25"=>"充值",
        "22"=>"线下付款",
        "23"=>"联系客服(该链接只有底部导航和首页浮动图标有效)",
        "17"=>"好店推荐",
        "16"=>"活动推荐",
        "7"=>"砍价商品",
        "8"=>"集卡商品",
        "9"=>"抢购商品",
        "10"=>"拼团商品",
        "13"=>"免单商品",
        "20"=>"专题详情",
        "11"=>"会员优惠券",
        "12"=>"其他小程序"
    );
    return $typearr;
}

function GetNoShowinput(){
    $typearr["js"] = "[1,2,3,4,5,14,15,16,17,18,19,21,22,23,24,25]";
    $typearr["php"] = array(1,2,3,4,5,14,15,16,17,18,19,21,22,23,24,25);
    return $typearr;
}

function time_tran($time,$timetype=1,$showtype="Y-m-d H:i:s"){
    $now_time = time();
    if($timetype==2){
        $show_time = strtotime($time);
    }else{
        $show_time = $time;
    }
    $default_time = date($showtype,$show_time);
    $dur = $now_time - $show_time;
    if($dur < 0){
        return $default_time; 
    }else{
        if($dur < 60){
            return $dur.'秒前'; 
        }else{
            if($dur < 3600){
                return floor($dur/60).'分钟前'; 
            }else{
                if($dur < 86400){
                    return floor($dur/3600).'小时前'; 
                }else{
                    if($dur < 259200){
                        return floor($dur/86400).'天前';
                    }else{
                        return $default_time; 
                    }
                }
            }
        }
    }
}

function encryptcode($string, $operation = 'D', $key = '', $expiry = 0) {   
    
    $ckey_length = 4;   
    
    $key = md5($key ? $key : "Xmzhy123@#$");
    
    $keya = md5(substr($key, 0, 16));   
    
    $keyb = md5(substr($key, 16, 16));   
    
    $keyc = $ckey_length ? ($operation == 'D' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';   
    
    $cryptkey = $keya.md5($keya.$keyc);   
    $key_length = strlen($cryptkey);   
    

    
    $string = $operation == 'D' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);   
    $result = '';   
    $box = range(0, 255);   
    $rndkey = array();   
    
    for($i = 0; $i <= 255; $i++) {   
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);   
    }   
    
    for($j = $i = 0; $i < 256; $i++) {   
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;   
        $tmp = $box[$i];   
        $box[$i] = $box[$j];   
        $box[$j] = $tmp;   
    }   
    
    for($a = $j = $i = 0; $i < $string_length; $i++) {   
        $a = ($a + 1) % 256;   
        $j = ($j + $box[$a]) % 256;   
        $tmp = $box[$a];   
        $box[$a] = $box[$j];   
        $box[$j] = $tmp;   
        
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
    }   
    if($operation == 'D') {  
        
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
            return substr($result, 26);   
        } else {   
            return '';   
        }   
    } else {   
        
        
        return $keyc.str_replace('=', '', base64_encode($result));   
    }   
} 



function exportToExcel($filename, $tileArray=array(), $dataArray=array()){  
    ini_set('memory_limit','512M');
    ini_set('max_execution_time',0);
    ob_end_clean();
    ob_start();
    header("Content-Type: text/csv");
    header("Content-Disposition:filename=".$filename);
    $fp=fopen('php://output','w');
    fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($fp,$tileArray);
    $index = 0;  
    foreach ($dataArray as $item) {  
        $index++;  
        fputcsv($fp,$item);  
    }  

    ob_flush();  
    flush();  
    ob_end_clean();  
}