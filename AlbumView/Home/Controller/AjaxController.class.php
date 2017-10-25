<?php
namespace Home\Controller;
use Think\Controller;
class AjaxController extends Controller {
    public function index(){
        $ajax_response = array();
      	$ajax_response['code'] = 403;
      	$ajax_response['message'] = 'Parameter missing ...';
      	$this->ajaxReturn($ajax_response,'xml');
    }
  	public function getinfo(){
    	if ($_SERVER['REQUEST_METHOD']!="POST") {
        	$ajax_response = array();
      		$ajax_response['code'] = 405;
      		$ajax_response['message'] = 'You need to request in POST mode ...';
          $this->ajaxReturn($ajax_response,'xml');
      }
      switch($_POST['act']) {
         case "thumb":
           $ajax_response = array();
           $ajax_response['code'] = 403;
           $ajax_response['message'] = 'This is a option that is still in development, please try again later.';
           $this->ajaxReturn($ajax_response,'xml');
           break;
         case "full":
           $ajax_handle=D('myalbum');
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
  	public function postmsg() {
      if($_SERVER['REQUEST_METHOD']!="POST") {
      	$ajaxcmt_results = array();
      	$ajaxcmt_results['code'] = 405;
      	$ajaxcmt_results['info'] = 'You need to request in POST mode ...';
      	$this->ajaxReturn($ajaxcmt_results,'xml');
      }
      if($_POST['name'] == '' || $_POST['ctu'] == '' || $_POST['message'] == '') {
       	$ajaxcmt_results = array();
      	$ajaxcmt_results['code'] = 403;
      	$ajaxcmt_results['info'] = 'Please make sure to provide required values ...';
      	$this->ajaxReturn($ajaxcmt_results,'xml');
      }
      $name=$_POST['name'];
      $ctu=$_POST['ctu'];
      $message=$_POST['message'];
      $cmt_info = array();
      $cmt_info['name'] = $name;
      $cmt_info['contact'] = $ctu;
      $cmt_info['message'] = $message;
      $cmt_info['pushtime'] = date('Y-m-d h:i:s');
      $comments_handle=M('myalbum_comments');
      $cmt_result=$comments_handle->add($cmt_info);
      if($cmt_result) {
        $cmt_results = array();
      	$cmt_results['code'] = 200;
      	$cmt_results['info'] = 'User reply successfully.';
      	$this->ajaxReturn($cmt_results,'xml');
      }
      else {
        $cmt_results = array();
      	$cmt_results['code'] = 500;
      	$cmt_results['info'] = 'Could not fetch your database server, please check your database server configure ...';
      	$this->ajaxReturn($cmt_results,'xml');
      }
    }
}