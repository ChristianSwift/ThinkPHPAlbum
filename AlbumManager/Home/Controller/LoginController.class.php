<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
  	public function index(){
		//$username = $_POST['username'];
		//$password = $_POST['encrypted'];
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
		//查询数据库中用户信息
		/*$users -> M('myalbum_users');
		$users -> $users->where("username='%d'",array($username))->select('password');
		if($password == $users[password]){
			$this->success('登录成功！正在进入后台管理系统。',__ROOT__.'/admin.php',1);
		}
		else{
			$this->error('用户名或者密码错误，请检查！',__ROOT__.'/admin.php',1);
		}*/
		$this -> display();
    }
}