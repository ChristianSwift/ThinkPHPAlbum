<?php
namespace Home\Controller;
use Think\Controller;
class RegsiterController extends Controller {
  	public function index() {
      	if(isset($_COOKIE['myalbum_uid']) || $_COOKIE['myalbum_uid']!='' || isset($_COOKIE['myalbum_token']) || $_COOKIE['myalbum_token']!=''){
        	$this->success('您已登录，正在为您自动跳转！',__ROOT__.'/admin.php?c=index');
          	exit();
        }
    	$this->display();
    }
  	public function ajaxRegsiter() {
		if($_SERVER['REQUEST_METHOD']!="POST") {
      		$reg_results = array();
       		$reg_results['code'] = 405;
       		$reg_results['info'] = 'You need to request in POST mode ...';
       		$this->ajaxReturn($reg_results,'xml');
    	}
      	if($_POST['username'] == '' || $_POST['userpwd'] == '' || $_POST['umail'] == '') {
        	$reg_results = array();
       		$reg_results['code'] = 403;
       		$reg_results['info'] = 'Please make sure to provide required values ...';
       		$this->ajaxReturn($reg_results,'xml');
        }
    	$username=$_POST['username'];
    	$user_handle=M('myalbum_users');
    	$usr_status = array();
    	$usr_status['username']=$username;
    	$usr_check=$user_handle->where($usr_status)->count();
    	if($usr_check) {
      		$reg_results = array();
       		$reg_results['code'] = 401;
       		$reg_results['info'] = 'User already exists ...';
       		$this->ajaxReturn($reg_results,'xml');
    	}
    	$userpwd=$_POST['userpwd'];
      	$usermail=$_POST['umail'];
    	$user_info = array();
    	$user_info['username'] = $username;
    	$user_info['userpwd'] = md5($userpwd);
      	$user_info['email'] = $usermail;
    	$reg_result=$user_handle->add($user_info);
    	if($reg_result) {
     		$reg_results = array();
       		$reg_results['code'] = 200;
       		$reg_results['info'] = 'User regsiter successfully.';
       		$this->ajaxReturn($reg_results,'xml');
    	}
    	else {
     		$reg_results = array();
       		$reg_results['code'] = 500;
       		$reg_results['info'] = 'Could not fetch your database server, please check your database server configure ...';
       		$this->ajaxReturn($reg_results,'xml');
    	}
	}
}