<?php
namespace Home\Controller;
use Think\Controller;
class UserManagerController extends WebAuthorityController {
  	public function index() {
      	if ($_SERVER['REQUEST_METHOD']=="POST") {
          if (!$_POST['newmail'] || $_POST['newmail'] == '' || !$_POST['newpwd'] || $_POST['newpwd'] == '' || !$_POST['uid'] || $_POST['uid'] == '') {
          	$this->error('请确保用户信息填写完整后再次尝试！');
          }
          $userdata = array();
          $userdata['userpwd'] = md5($_POST['newpwd']);
          $userdata['email'] = $_POST['newmail'];
          $user_handle=D('myalbum_users');
          $userinfo_update=$user_handle->where('uid='.$_POST['uid'])->setField($userdata);
          if ($userinfo_update) {
            echo '<div id="LogoutHiddenIframe" style="display: none"><iframe src="'.__ROOT__.'/admin.php?c=Login&a=ajaxLogout"></iframe></div>';
          	$this->success('用户信息已被成功变更！现在，请您重新登录系统。');
            exit();
          }
          else {
          	$this->error('无法变更该用户信息，请检查您是否有权限控制账户。');
          }
      	}
      	if (!$_GET['uid'] || $_GET['uid'] == '') {
        	$this->error('无效的请求，正在返回。');
        }
    	$this->display();
    }
  	public function pullUserList() {
    	if ($_SERVER['REQUEST_METHOD']!="POST") {
        	$ajax_response = array();
      		$ajax_response['code'] = 405;
      		$ajax_response['message'] = 'You need to request in POST mode ...';
          $this->ajaxReturn($ajax_response,'xml');
      }
      switch($_POST['act']) {
         case "mini":
           $ajax_response = array();
           $ajax_response['code'] = 403;
           $ajax_response['message'] = 'This is a option that is still in development, please try again later.';
           $this->ajaxReturn($ajax_response,'xml');
           break;
         case "full":
           $ajax_handle=D('myalbum_users');
           $ajax_result=$ajax_handle->select();
           if ($ajax_result) {
            $this->ajaxReturn($ajax_result,'xml');
           }
           else {
             $ajax_response = array();
             $ajax_response['code'] = 500;
             $ajax_response['message'] = 'Could not fetch your database server, please check your database server configure ...';
             $this->ajaxReturn($ajax_response,'xml');
           }
           break;
         default:
          $ajax_response = array();
          $ajax_response['code'] = 403;
      	  $ajax_response['message'] = 'Your request is unavailable ...';
         	$this->ajaxReturn($ajax_response,'xml');
          break;
       }
    }
}