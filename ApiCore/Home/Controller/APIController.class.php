<?php
namespace Home\Controller;
use Think\Controller;
class APIController extends Controller {
  	private function checkPerm() {
    	if(!isset($_COOKIE['myalbum_token']) || $_COOKIE['myalbum_token'] == '') {
			$arr = array(
				'code'	=>	403,
				'message'	=>	'您没有相应权限访问该接口，请传入有效的token或登录有效帐号后再次尝试。',
				'requestId'	=>	date('YmdHis',time())
			);
          	self::api($arr);
		}
	}
	private function api($data = null) {
		if($data == null) {
			$arr = array(
				'code'	=>	500,
				'message'	=>	'',
				'requestId'	=>	date('YmdHis',time())
			);
		}
		$this->ajaxReturn($data, 'xml');
	}
}