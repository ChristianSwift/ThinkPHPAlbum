<?php
namespace Home\Controller;
use Think\Controller;
class WebAuthorityController extends Controller {
  	public function _initialize() {
    	if(!isset($_COOKIE['myalbum_token']) || $_COOKIE['myalbum_token'] == ''){
          	$this->error('您没有登陆，正在跳转到登录页。',__ROOT__.'/admin.php?c=Login', 1); //尚未登录时跳回登录页面，防止URL输入恶意访问后台管理系统
		}
    }
}