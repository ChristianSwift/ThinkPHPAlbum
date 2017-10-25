<?php
namespace Home\Controller;
use Think\Controller;
class OperationController extends WebAuthorityController {
  	public function index() {
      	$response = array();
       	$response['code'] = 405;
       	$response['info'] = 'You need to request by some parameters ...';
       	$this->ajaxReturn($response,'xml');
    }
  	public function upload() {
      if($_SERVER['REQUEST_METHOD']!="POST") {
      	$upload_results = array();
      	$upload_results['code'] = 405;
      	$upload_results['info'] = 'You need to request in POST mode ...';
      	$this->ajaxReturn($upload_results,'xml');
      }
      if($_POST['full_url'] == '' || $_POST['thumb_url'] == '' || $_POST['img_description'] == '' || $_POST['upload_time'] == '') {
       	$upload_results = array();
      	$upload_results['code'] = 403;
      	$upload_results['info'] = 'Please make sure to provide required values ...';
      	$this->ajaxReturn($upload_results,'xml');
      }
      $furl=$_POST['full_url'];
      $turl=$_POST['thumb_url'];
      $dinfo=$_POST['img_description'];
      $mtime=$_POST['upload_time'];
      $picdata = array();
      $picdata['furl'] = $furl;
      $picdata['turl'] = $turl;
      $picdata['dinfo'] = $dinfo;
      $picdata['mtime'] = $mtime;
      $upload_handle=M('myalbum');
      $upload_result=$upload_handle->add($picdata);
      if($upload_result) {
        $upload_results = array();
      	$upload_results['code'] = 200;
      	$upload_results['info'] = 'User reply successfully.';
      	$this->ajaxReturn($upload_results,'xml');
      }
      else {
        $upload_results = array();
      	$upload_results['code'] = 500;
      	$upload_results['info'] = 'Could not fetch your database server, please check your database server configure ...';
      	$this->ajaxReturn($upload_results,'xml');
      }
    }
	public function localupload() {
		if($_SERVER['REQUEST_METHOD']!="POST") {
			$upload_results = array();
			$upload_results['code'] = 405;
			$upload_results['info'] = 'You need to request in POST mode ...';
			$this->ajaxReturn($upload_results,'xml');
		}
		if(!isset($_POST['img_description']) or !isset($_POST['upload_time'])) {
			$this->error("缺少必要参数，本次操作失败。");
		}
		//import('ORG.Net.UploadFile');
		$upload = new \Think\Upload();//上传实例化
		$upload->maxSize = 8388608 ;//设置附件上传大小
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');//设置附件上传类型
		$upload->rootPath = './Public/';//设置附件上传根目录
		$upload->savePath = 'Uploads/';//设置附件上传目录
		$upload->replace = true;//覆盖同名文件
		$upload_result = $upload->upload();
		if(!$upload_result) {//上传错误提示错误信息
			$this->error($upload->getError());
		}
		$upload_urlpath = $upload->rootPath.$upload_result['img_binary']['savepath'].$upload_result['img_binary']['savename'];//拼装文件路径URL
		$furl = $upload_urlpath;
		$turl = $upload_urlpath;
		$dinfo = $_POST['img_description'];
		$mtime = $_POST['upload_time'];
		$picdata = array();
		$picdata['furl'] = $furl;
		$picdata['turl'] = $turl;
		$picdata['dinfo'] = $dinfo;
		$picdata['mtime'] = $mtime;
		$upload_handle=M('myalbum');
		$upload_result=$upload_handle->add($picdata);
		$this->success('恭喜您，相片上传成功！');
	}
  	public function remove() {
      if($_SERVER['REQUEST_METHOD']!="POST") {
      	$upload_results = array();
      	$upload_results['code'] = 405;
      	$upload_results['info'] = 'You need to request in POST mode ...';
      	$this->ajaxReturn($upload_results,'xml');
      }
      if($_POST['id'] == '' || $_POST['type'] == '') {
       	$upload_results = array();
      	$upload_results['code'] = 403;
      	$upload_results['info'] = 'Please make sure to provide required values ...';
      	$this->ajaxReturn($upload_results,'xml');
      }
      switch($_POST['type']) {
        case "photo":
          $id=$_POST['id'];
          $sql_handle=M('myalbum');
          $del_result=$sql_handle->where('id='.$id)->delete();
          if($del_result) {
          	$del_results = array();
      		$del_results['code'] = 200;
      		$del_results['info'] = 'Photo remove successfully.';
      		$this->ajaxReturn($del_results,'xml');
          }
          else {
          	$del_results = array();
      		$del_results['code'] = 500;
      		$del_results['info'] = 'Photo remove failed.';
      		$this->ajaxReturn($del_results,'xml');
          }
          break;
        case "comment":
          $id=$_POST['id'];
          $sql_handle=M('myalbum_comments');
          $del_result=$sql_handle->where('cid='.$id)->delete();
          if($del_result) {
          	$del_results = array();
      		$del_results['code'] = 200;
      		$del_results['info'] = 'Comment remove successfully.';
      		$this->ajaxReturn($del_results,'xml');
          }
          else {
          	$del_results = array();
      		$del_results['code'] = 500;
      		$del_results['info'] = 'Comment remove failed.';
      		$this->ajaxReturn($del_results,'xml');
          }
          break;
        default:
          $upload_results = array();
      	  $upload_results['code'] = 403;
      	  $upload_results['info'] = 'Please make sure to provide required values ...';
      	  $this->ajaxReturn($upload_results,'xml');
          break;
      }
    }
	public function getcmts() {
      if($_SERVER['REQUEST_METHOD']!="POST") {
          $gcmt_results = array();
          $gcmt_results['code'] = 405;
          $gcmt_results['info'] = 'You need to request in POST mode ...';
          $this->ajaxReturn($gcmt_results,'xml');
      }
      if($_POST['act'] == '') {
        $gcmt_results = array();
        $gcmt_results['code'] = 403;
        $gcmt_results['info'] = 'Please make sure to provide required values ...';
        $this->ajaxReturn($gcmt_results,'xml');
      }
      switch($_POST['act']) {
         case "thumb":
           $ajax_response = array();
           $ajax_response['code'] = 403;
           $ajax_response['message'] = 'This is a option that is still in development, please try again later.';
           $this->ajaxReturn($ajax_response,'xml');
           break;
         case "full":
           $ajax_handle=D('myalbum_comments');
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