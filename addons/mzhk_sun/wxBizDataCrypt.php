<?php




include_once "pkcs7Encoder.php";
include_once "errorCode.php";


class WXBizDataCrypt
{
    private $appid;
	private $sessionKey;

	
	public function WXBizDataCrypt( $appid, $sessionKey)
	{
		$this->sessionKey = $sessionKey;
		$this->appid = $appid;
	}


	
	public function decryptData( $encryptedData, $iv, &$data )
	{
		if (strlen($this->sessionKey) != 24) {
			return ErrorCode::$IllegalAesKey;
		}
		$aesKey=base64_decode($this->sessionKey);

        
		if (strlen($iv) != 24) {
			return ErrorCode::$IllegalIv;
		}
		$aesIV=base64_decode($iv);

		$aesCipher=base64_decode($encryptedData);

		$pc = new Prpcrypt($aesKey);
		$result = $pc->decrypt($aesCipher,$aesIV);
        
		if ($result[0] != 0) {
			return $result[0];
		}
     
        $dataObj=json_decode( $result[1] );
        if( $dataObj  == NULL )
        {
            return ErrorCode::$IllegalBuffer;
        }
        if( $dataObj->watermark->appid != $this->appid )
        {
            return ErrorCode::$IllegalBuffer;
        }
		$data = $result[1];
		return ErrorCode::$OK;
	}

}

