<?php

include_once "errorCode.php";


class PKCS7Encoder
{
	public static $block_size = 16;

	
	function encode( $text )
	{
		$block_size = PKCS7Encoder::$block_size;
		$text_length = strlen( $text );
		
		$amount_to_pad = PKCS7Encoder::$block_size - ( $text_length % PKCS7Encoder::$block_size );
		if ( $amount_to_pad == 0 ) {
			$amount_to_pad = PKCS7Encoder::block_size;
		}
		
		$pad_chr = chr( $amount_to_pad );
		$tmp = "";
		for ( $index = 0; $index < $amount_to_pad; $index++ ) {
			$tmp .= $pad_chr;
		}
		return $text . $tmp;
	}

	
	function decode($text)
	{

		$pad = ord(substr($text, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		return substr($text, 0, (strlen($text) - $pad));
	}

}


class Prpcrypt
{
	public $key;

	function Prpcrypt( $k )
	{
		$this->key = $k;
	}

	
	public function decrypt( $aesCipher, $aesIV )
	{

		try {
			
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			
			mcrypt_generic_init($module, $this->key, $aesIV);

			
			$decrypted = mdecrypt_generic($module, $aesCipher);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
		} catch (Exception $e) {
			return array(ErrorCode::$IllegalBuffer, null);
		}


		try {
			
			$pkc_encoder = new PKCS7Encoder;
			$result = $pkc_encoder->decode($decrypted);

		} catch (Exception $e) {
			
			return array(ErrorCode::$IllegalBuffer, null);
		}
		return array(0, $result);
	}
}

?>