<?php
namespace Home\Controller;
use Think\Controller;
class APIController extends Controller {
  	function checkPerm() {
    	if(!isset($_COOKIE['myalbum_token']) || $_COOKIE['myalbum_token'] == '') {
			$arr = array(
				'code'	=>	403,
				'message'	=>	'您没有相应权限访问该接口，请传入有效的token或登录有效帐号后再次尝试。',
				'requestId'	=>	date('YmdHis',time())
			);
          	self::api($arr);
		}
	}
	function api($data = null) {
		if($data == null) {
			$arr = array(
				'code'	=>	500,
				'message'	=>	'接口输出失败，数据返回处于null状态。',
				'requestId'	=>	date('YmdHis',time())
			);
			$this->ajaxReturn($arr, 'xml');
		}
		$this->ajaxReturn($data, 'xml');
	}
}