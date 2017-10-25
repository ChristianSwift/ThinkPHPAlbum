<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
  	public function index() {
      	if(isset($_COOKIE['myalbum_uid']) || $_COOKIE['myalbum_uid']!='' || isset($_COOKIE['myalbum_token']) || $_COOKIE['myalbum_token']!=''){
        	$this->success('您已登录，正在为您自动跳转！',__ROOT__.'/admin.php?c=index');
          	exit();
        }
    	$this->display();
    }
  	public function ajaxLogin() {
      	if($_SERVER['REQUEST_METHOD']!="POST") {
      		$ajaxlogin_results = array();
       		$ajaxlogin_results['code'] = 405;
       		$ajaxlogin_results['info'] = 'You need to request in POST mode ...';
       		$this->ajaxReturn($ajaxlogin_results,'xml');
    	}
      	if($_POST['username'] == '' || $_POST['userpwd'] == '') {
        	$ajaxlogin_results = array();
       		$ajaxlogin_results['code'] = 403;
       		$ajaxlogin_results['info'] = 'Please make sure to provide required values ...';
       		$this->ajaxReturn($ajaxlogin_results,'xml');
        }
    	$username=$_POST['username'];
      	$userpwd=$_POST['userpwd'];
      	$user_handle=M('myalbum_users');
      	$user_info = array();
      	$user_info['username'] = $username;
      	$user_info['userpwd'] = md5($userpwd);
      	$login_result=$user_handle->where($user_info)->find();
      	if($login_result) {
          	$usertoken = date('Ymdhis',time());
          	setcookie("myalbum_uid", $login_result['uid'], time() + 3600,  "/");
          	setcookie("myalbum_token", $usertoken, time() + 3600,  "/");
          	$token_updstr['usertoken'] = $usertoken;
			$user_handle->where('uid='.$login_result['uid'])->save($token_updstr);
			$ajaxlogin_results = array();
          	$ajaxlogin_results['code'] = 200;
          	$ajaxlogin_results['info'] = 'Login successfully ...';
          	$this->ajaxReturn($ajaxlogin_results,'xml');
        }
      	else {
        	$ajaxlogin_results = array();
          	$ajaxlogin_results['code'] = 401;
          	$ajaxlogin_results['info'] = 'Incorrect account name or password ...';
          	$this->ajaxReturn($ajaxlogin_results,'xml');
        }
    }
  	public function ajaxLogout() {
    	if(!isset($_COOKIE['myalbum_uid']) || $_COOKIE['myalbum_uid']=='' || !isset($_COOKIE['myalbum_token']) || $_COOKIE['myalbum_token']==''){
          	$logout_results = array();
      		$logout_results['code'] = 403;
      		$logout_results['info'] = 'No need for logout operation.';
      		$this->ajaxReturn($logout_results,'xml');
		}
      	setcookie("myalbum_uid", "", time() - 3600,  "/");
        setcookie("myalbum_token", "", time() - 3600,  "/");
      	$_SESSION=array();
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(),'',time()-1,'/');
		}
		session_destroy();
      	$logout_results = array();
      	$logout_results['code'] = 200;
      	$logout_results['info'] = 'User logout successfully.';
      	$this->ajaxReturn($logout_results,'xml');
    }
  	public function ajaxSSO() {
    	//TODO
    }
  	public function SSOCallback() {
    	//TODO
    }
  	public function ajaxSSOLogout() {
    	//TODO
    }
}