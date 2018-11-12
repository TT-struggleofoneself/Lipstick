<?php
/**
 * hc_step模块处理程序
 *
 * @author huichuang
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT."/addons/hc_step/functions.php"; 
class Hc_stepModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		if($content){
			$account_api = WeAccount::create();
			$token = $account_api->getAccessToken();
			$model = new HcfkModel();
	    	$follow  = pdo_get('hcstep_kefu',array('uniacid'=>$_W['uniacid'],'kefu_keyword'=>$content));

			$post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
			$post_data = $model->json_encode2(array(
				'touser' =>$this->message['from'],
				'msgtype' => 'link',
				'link' => array(
					'title'       => $follow['kefu_title'],
					'description' => $follow['kefu_gaishu'],
					'url'         => $follow['kefu_url'],
					'thumb_url'   => toimage($follow['kefu_img']),
				),
			));
			ihttp_post($post_url,$post_data);
			echo success;
		}else{
			$account_api = WeAccount::create();
			$token = $account_api->getAccessToken();
			$model = new HcfkModel();
	    	$follow  = json_decode(pdo_get('hcstep_set',array('uniacid'=>$_W['uniacid'])),true);

			$post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
			$post_data = $model->json_encode2(array(
				'touser' =>$this->message['from'],
				'msgtype' => 'text',
				'text' => array(
					'content' => '敬请期待'
				),
			));
			ihttp_post($post_url,$post_data);
			echo success;
		}

	}
}