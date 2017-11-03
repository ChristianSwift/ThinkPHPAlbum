<?php
namespace Home\Controller;
use Think\Controller;
class RegisterController extends Controller {
  	public function index(){
		//检测用户数据库是否被初始化
		$users = M('myalbum_users');
		$userinfo = $users->select();
		if($userinfo != null) {
			$this->error('系统已经完成账户初始化，无需重复注册。',__ROOT__.'/admin.php?c=Login', 1); //尚未登录时跳回登录页面，防止URL输入恶意访问后台管理系统
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