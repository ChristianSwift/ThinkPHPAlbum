<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
  	public function index(){
		//免登跳转逻辑
		if(cookie("myalbum_token") != null || session("myalbum_token") != null){
			if (cookie("myalbum_token") == session("myalbum_token")) {
				$this->success('您已登录系统，正在为您自动跳转。',__ROOT__.'/admin.php', 2); //已登录时自动跳转至后台，避免多次反复登录
				return true;
			}
		}
		//检测用户数据库是否被初始化
		$users = M('myalbum_users');
		$userinfo = $users->select();
		if($userinfo != null) {
			$this -> assign('myalbum_regStatus','none');
		}
		else {
			$this -> assign('myalbum_regStatus','block');
		}
		//查询网站基础内容
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		$this -> display();
    }
}