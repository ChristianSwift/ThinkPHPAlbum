<?php
namespace Home\Controller;
use Think\Controller;
class WebAuthorityController extends Controller {
  	public function _initialize() {
    	if(cookie("myalbum_token") == null || session("myalbum_token") == null){
          	$this->error('您没有登陆，正在跳转到登录页。',__ROOT__.'/admin.php?c=Login', 1); //尚未登录时跳回登录页面，防止URL输入恶意访问后台管理系统
		}
		else if (cookie("myalbum_token") != session("myalbum_token")) {
			session("myalbum_token",null);
			cookie('myalbum_token',null);
			$this->error('系统检测到您的登录状态存在异常，已为你自动退出！请重新登录。',__ROOT__.'/admin.php?c=Login', 1); //尚未登录时跳回登录页面，防止URL输入恶意访问后台管理系统
		}
		else {
			$sessionId = md5(session("myalbum_token").date("YmdH",time())); //成功登录后产生sessionId（仅1小时内有效），后台任何需要操作数据库的功能理论均需携带此sessionId
		}
    }
}