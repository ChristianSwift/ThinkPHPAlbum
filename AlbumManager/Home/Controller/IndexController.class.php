<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends WebAuthorityController {
  	public function index() {
    	$this->display();
    }
  	public function picmgr() {
      	$this->assign('upload_srvtime',date('Y-m-d h:i:s',time()));
    	$this->display();
    }
  	public function comments() {
    	$this->display();
    }
  	public function usercenter() {
    	$this->display();
    }
  	public function settings() {
      	if($_SERVER['REQUEST_METHOD']=="POST") {
        	/*
            C('myalbum_name',$_POST['album_name']);
          	C('myalbum_introduction',$_POST['album_introduction']);
          	C('myalbum_twitter',$_POST['album_twitter']);
          	C('myalbum_facebook',$_POST['album_facebook']);
          	C('myalbum_github',$_POST['album_github']);
          	C('myalbum_author',$_POST['album_author']);
          	C('myalbum_bgm',$_POST['album_bgm']);
          	C('myalbum_copyright',$_POST['album_copyright']);
          	$this->success('系统配置文件修改成功！正在返回。');
            */
          	$this->error('当前版本暂不支持在线修改配置文件，请手动编辑应用配置文件：conf/config.php 。');
          	exit();
        }
      	$this->assign('album_name',C('myalbum_name'));
      	$this->assign('album_introduction',C('myalbum_introduction'));
      	$this->assign('album_twitter',C('myalbum_twitter'));
      	$this->assign('album_facebook',C('myalbum_facebook'));
      	$this->assign('album_github',C('myalbum_github'));
      	$this->assign('album_author',C('myalbum_author'));
      	$this->assign('album_bgm',C('myalbum_bgm'));
      	$this->assign('album_copyright',C('myalbum_copyright'));
    	$this->display();
    }
}