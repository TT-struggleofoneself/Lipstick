<?php 
class WeixinfirmPay {

    protected $mch_appid;
    protected $mchid;
    protected $key;
    protected $openid;
    protected $partner_trade_no;
    protected $re_user_name;
    protected $desc;
    protected $amount;
    protected $apiclient_cert;
    protected $apiclient_key;

    function __construct($mch_appid, $mchid, $key, $openid,$partner_trade_no,$re_user_name,$desc,$amount,$apiclient_cert,$apiclient_key) {
        $this->mch_appid = $mch_appid;
        $this->mchid = $mchid;
        $this->key = $key;
        $this->openid = $openid;
        $this->partner_trade_no = $partner_trade_no;
        $this->re_user_name = $re_user_name;
        $this->desc = $desc;
        $this->amount = $amount;
        $this->apiclient_cert = $apiclient_cert;
        $this->apiclient_key = $apiclient_key;
    }

    public function pay() {
        
        $return = $this->unifiedorder();
        return $return;
    }

    
    private function unifiedorder() {
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $parameters = array(
            'mch_appid' => $this->mch_appid, 
            'mchid' => $this->mchid, 
            'nonce_str' => $this->createNoncestr(), 
            'partner_trade_no'=> $this->partner_trade_no,
            'openid' => $this->openid, 
            'check_name' => 'NO_CHECK', 
            're_user_name' => $this->re_user_name, 
            'amount' => $this->amount,
            'desc' => $this->desc,
            'spbill_create_ip' => '120.79.152.105', 
        );
        
        $parameters['sign'] = $this->getSign($parameters);
        $xmlData = $this->arrayToXml($parameters);
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60,$this->apiclient_cert,$this->apiclient_key));
        return $return;
    }

    private static function postXmlCurl($xml, $url, $second = 30,$apiclient_cert,$apiclient_key){
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSLCERT,$apiclient_cert); 
        curl_setopt($ch, CURLOPT_SSLKEY,$apiclient_key); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);
        
        $data = curl_exec($ch);
        
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
    
    
    private function arrayToXml($arr) {
        $xml = "<root>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</root>";
        return $xml;
    }

    
    private function xmlToArray($xml) {
        
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    
    private function createNoncestr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    
    private function getSign($Obj) {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        
        $String = $String . "&key=" . $this->key;
        
        $String = md5($String);
        
        $result_ = strtoupper($String);
        return $result_;
    }

    
    private function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }


}		
			
		
