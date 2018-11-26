<?php
class HcfkModel{



	function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $str .= $strPol[rand(0, $max)]; // rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    /*
     * 生成签名
     */
    function getSign($Obj,$api_key)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        // 签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        // echo "【string】 =".$String."</br>";
        // 签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $api_key;
        // echo "<textarea style='width: 50%; height: 150px;'>$String</textarea> <br />";
        // 签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    
    /*
     * 生成签名
     */
    function getSign1($Obj,$api_key)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        // 签名步骤一：按字典序排序参数
        ksort($Parameters);
        //$String = $this->formatBizQueryParaMap($Parameters, false);
        $String  = "appId=".$Obj['appId']."&nonceStr=".$Obj['nonceStr']."&package=".$Obj['package']."&signType=MD5&timeStamp=".$Obj['timeStamp']; 
        // echo "【string】 =".$String."</br>";
        // 签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $api_key;
        // echo "<textarea style='width: 50%; height: 150px;'>$String</textarea> <br />";
        // 签名步骤三：MD5加密
        //echo $String;
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    /*
     * 获取当前服务器的IP
     */
    function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    // 数组转xml
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    function array2Xml($arr){ 
        $xml = "<xml>"; 
        foreach ($arr as $key=>$val){ 
            if(is_array($val)){ 
                $xml.="<".$key.">".arrayToXml($val)."</".$key.">"; 
            }else{ 
                $xml.="<".$key.">".$val."</".$key.">"; 
            }
        }
        $xml.="</xml>"; 
        return $xml; 
    }

    // post https请求，CURLOPT_POSTFIELDS xml格式

    function postXmlCurl($xml, $url, $second = 30)
    {
        // 初始化curl
        $ch = curl_init();
        // 超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        // 这里设置代理，如果有的话
        // curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        // curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        // 运行curl
        $data = curl_exec($ch);
        // 返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
                echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
                    curl_close($ch);
                    return false;
        }

    }

    /**
     * xml转成数组
     */
    function xmlstr_to_array($xmlstr)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        return $val;
    }

    // 将数组转成uri字符串
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    function domnode_to_array($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i ++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (! isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } elseif ($v) {
                        $output = (string) $v;
                    }
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = array();
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
    /**
     * 生成分享小程序码图片
     * @param  [string] $bg         背景图片
     * @param  [string] $avatar_url 用户头像
     * @param  [string] $wxapp_url  小程序码
     * @param  [array]  $text       红包文字详情
     * @param  [string] $sign       小程序签名
     */
    function qrcode($bg,$avatar_url,$wxapp_url,$power,$filename) {
        //header("Content-type: image/png");
        
        $font = IA_ROOT."/addons/hc_answer/public/font.TTF";
        //背景图片处理
        $size = getimagesize($bg);
        $target = imagecreatetruecolor($size[0], $size[1]);
        $bg = @imagecreatefromjpeg($bg);
        imagecopy($target, $bg, 0, 0, 0, 0,$size[0], $size[1]);
        imagedestroy($bg);

        //头像描述框
        $adburl = IA_ROOT."/addons/hc_answer/public/ab.png";
        $adb = @imagecreatefrompng($adburl);
        $adb_size = getimagesize($adburl);
        $adb_w = 864;
        $adb_h = 170;
        $adb_x = $size[0]/2-$adb_w/2;
        $adb_y = 80;
        imagecopyresized($target, $adb, $adb_x, $adb_y, 0, 0,$adb_w, $adb_h,$adb_size[0],$adb_size[1]);
        imagedestroy($adb);


        //头像处理
        $avatar = file_get_contents($avatar_url);//获取远程图片
        $avatar = @imagecreatefromstring($avatar);
        $avatar_w = $avatar_h = 150;
        $avatar_x = ceil(($size[0]-$avatar_w)/2);
        $avatar_y = 200;

        //图片实际宽高
        $avatar_size = getimagesize($avatar_url);
        $w = $avatar_size[0];
        $h = $avatar_size[1];
        //生成圆形图片
        $wxpic = imagecreatetruecolor($w,$h);  
        imagealphablending($wxpic,false);  
        $transparent = imagecolorallocatealpha($wxpic, 0, 0, 0, 127);  

        $r=$w/2;
        for($x=0;$x<$w;$x++){
            for($y=0;$y<$h;$y++){
                $c = imagecolorat($avatar,$x,$y);
                $_x = $x - $w/2;
                $_y = $y - $h/2;
                if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){
                    imagesetpixel($wxpic,$x,$y,$c);
                }else{
                    imagesetpixel($wxpic,$x,$y,$transparent);
                }
            }
        }
        imagesavealpha($wxpic, true);  
        imagecopyresized($target, $wxpic, 115, 90, 0, 0,$avatar_w, $avatar_h,$avatar_size[0],$avatar_size[1]);
        imagedestroy($avatar);
        imagedestroy($wxpic);  

        
        //描述
        $desc = $power['desc'];
        $fontSize  = 38;        
        $font_color = imagecolorallocate($target,144,186,244); 
        $fontWidth = imagettfbbox($fontSize,0,$font,$desc[0]);//文字宽度
        imagettftext($target, $fontSize, 0, 340, 150, $font_color, $font, $desc[0]); 


        
        $font_color = imagecolorallocate($target,81,83,140); 
        $fontWidth = imagettfbbox($fontSize,0,$font,$desc[1]);//文字宽度
        imagettftext($target, $fontSize, 0, 340, 215, $font_color, $font, $desc[1]); 


        $x = $size[0]/2;//x轴
        $y = $size[1]/2-28;//y轴
        $max = 270;
        $sc = $power['sc'];

        $image = imagecreatetruecolor($size[0], $size[1]);
        $hbg = imagecolorallocatealpha($image, 255, 0, 0);
        imagefill($image,0,0,$hbg);
        imagecolortransparent($image,$hbg);
        $col_poly = imagecolorallocate($image,85,191,251);
        
        imagefilledpolygon($image,
                     array (
                            $x, $y-$sc[0],
                            $x+($sc[1]*cos(deg2rad(30))),$y-($sc[1]*sin(deg2rad(30))),
                            $x+($sc[2]*cos(deg2rad(30))),$y+($sc[2]*sin(deg2rad(30))),
                            $x,$y+$sc[3],
                            $x-($sc[4]*cos(deg2rad(30))),$y+($sc[4]*sin(deg2rad(30))),
                            $x-($sc[5]*cos(deg2rad(30))),$y-($sc[5]*sin(deg2rad(30))),
                     ), 6, $col_poly);
        imagecopyresized($target, $image, 0, 0, 0, 0,$size[0], $size[1],$size[0], $size[1]);
        //imagejpeg($image);

        //能力图
        $powers = IA_ROOT."/addons/hc_answer/public/power.png";
        $adb = @imagecreatefrompng($powers);
        $adb_size = getimagesize($powers);
        $adb_w = $adb_h = 550;
        $adb_x = $size[0]/2-$adb_w/2;
        $adb_y = 460;
        imagecopyresized($target, $adb, $adb_x, $adb_y, 0, 0,$adb_w, $adb_h,$adb_size[0],$adb_size[1]);
        imagedestroy($adb);


        $cate = $power['cate'];

        $fontSize  = 30;
        $font_color = imagecolorallocate($target,255,255,255); 
        $fontWidth = imagettfbbox($fontSize,0,$font,$cate[0]);//文字宽度
        imagettftext($target, $fontSize, 0, 500, 440, $font_color, $font, $cate[0]); 

        $font_color = imagecolorallocate($target,255,255,255);
        $fontWidth = imagettfbbox($fontSize,0,$font,$cate[1]);//文字宽度
        imagettftext($target, $fontSize, 0, 210, 580, $font_color, $font, $cate[1]); 

        $font_color = imagecolorallocate($target,255,255,255);
        $fontWidth = imagettfbbox($fontSize,0,$font,$cate[2]);//文字宽度
        imagettftext($target, $fontSize, 0, 780, 580, $font_color, $font, $cate[2]); 

        $font_color = imagecolorallocate($target,255,255,255); 
        $fontWidth = imagettfbbox($fontSize,0,$font,$cate[3]);//文字宽度
        imagettftext($target, $fontSize, 0, 210, 920, $font_color, $font, $cate[3]); 

        $font_color = imagecolorallocate($target,255,255,255);
        $fontWidth = imagettfbbox($fontSize,0,$font,$cate[4]);//文字宽度
        imagettftext($target, $fontSize, 0, 780, 920, $font_color, $font, $cate[4]); 

        $font_color = imagecolorallocate($target,255,255,255);
        $fontWidth = imagettfbbox($fontSize,0,$font,$cate[5]);//文字宽度
        imagettftext($target, $fontSize, 0, 500, 1060, $font_color, $font, $cate[5]); 

            
        
        //底部签名
        $fontSize  = 32;
        $font_color = imagecolorallocate($target,81,83,140); 
        imagettftext($target, $fontSize, 0, 730, 1470, $font_color, $font, '长按扫码来挑战'); 

        //小程序码处理

        $wxapp = file_get_contents($wxapp_url);//获取远程图片
        $wxapp = @imagecreatefromstring($wxapp);
        $avatar_w = $avatar_h = 315;
        $avatar_x = ceil(($size[0]-$avatar_w)/2);
        $avatar_y = 900;

        //图片实际宽高
        $avatar_size = getimagesize($wxapp_url);
        $w = $avatar_size[0];
        $h = $avatar_size[1];
        //生成圆形图片
        $wxpic = imagecreatetruecolor($w,$h);  
        imagealphablending($wxpic,false);  
        $transparent = imagecolorallocatealpha($wxpic, 0, 0, 0, 127);  

        $r=$w/2;
        for($x=0;$x<$w;$x++){
            for($y=0;$y<$h;$y++){
                $c = imagecolorat($wxapp,$x,$y);
                $_x = $x - $w/2;
                $_y = $y - $h/2;
                if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){
                    imagesetpixel($wxpic,$x,$y,$c);
                }else{
                    imagesetpixel($wxpic,$x,$y,$transparent);
                }
            }
        }
        imagesavealpha($wxpic, true);  
        imagecopyresized($target, $wxpic, 730, 1100, 0, 0,$avatar_w, $avatar_h,$avatar_size[0],$avatar_size[1]);

        imagedestroy($wxapp);
        imagedestroy($wxpic);
        $dir = IA_ROOT.'/addons/hc_answer/upload/';
        if(!file_exists($dir)){
           mkdir($dir,0777,true);
        }
        //imagejpeg($target);
        imagejpeg($target,IA_ROOT."/addons/hc_answer/upload/".$filename);  
        imagedestroy($target);
        return "addons/hc_answer/upload/".$filename;
    }

    /**
     * 生成小程序码
     * @return [type] [description]
     */
    public function wxappqrcode($uid){

        global $_W;
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();      
      	
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$token;
        $params = array();
        $params['scene'] = "user_id=".$uid;
        $params['width'] = 300;
        $params['page'] = "hc_answer/pages/index/index";
      
        $result = ihttp_post($url, json_encode($params));
        $res = json_decode($result['content'],true);

        if($res['errcode'] == '41030'){
            return 'https://we10.66bbn.com/addons/hc_answer/public/qrcode.jpg';
        }else{
            $dir = IA_ROOT.'/addons/hc_answer/upload/';
            if(!file_exists($dir)){
                mkdir($dir,0777,true);
            }
            file_put_contents(IA_ROOT.'/addons/hc_answer/upload/'.$uid.'.jpg',$result['content']);
            return $_W['siteroot'].'addons/hc_answer/upload/'.$uid.'.jpg'; 
        }
    }

    function randomFloat($min,$max){  
       $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);  
       return sprintf("%.2f", $num);
    }


    function json_encode2($array){
        if(version_compare(PHP_VERSION,'5.4.0','<')){
            $str = json_encode($array);
            $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i",function($matchs){
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
            },$str);
            return $str;
        }else{
            return json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }
    //不同环境下获取真实的IP
    function get_ip(){
        //判断服务器是否允许$_SERVER
        if(isset($_SERVER)){    
            if(isset($_SERVER[HTTP_X_FORWARDED_FOR])){
                $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
            }elseif(isset($_SERVER[HTTP_CLIENT_IP])) {
                $realip = $_SERVER[HTTP_CLIENT_IP];
            }else{
                $realip = $_SERVER[REMOTE_ADDR];
            }
        }else{
            //不允许就使用getenv获取  
            if(getenv("HTTP_X_FORWARDED_FOR")){
                  $realip = getenv( "HTTP_X_FORWARDED_FOR");
            }elseif(getenv("HTTP_CLIENT_IP")) {
                  $realip = getenv("HTTP_CLIENT_IP");
            }else{
                  $realip = getenv("REMOTE_ADDR");
            }
        }

        return $realip;
    }  
}