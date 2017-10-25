<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
  	public function index(){
      	$this->assign('myalbum_name',C('myalbum_name'));
      	$this->assign('myalbum_introduction',C('myalbum_introduction'));
      	$this->assign('myalbum_twitter',C('myalbum_twitter'));
      	$this->assign('myalbum_facebook',C('myalbum_facebook'));
      	$this->assign('myalbum_github',C('myalbum_github'));
      	$this->assign('myalbum_author',C('myalbum_author'));
      	$this->assign('myalbum_bgm',C('myalbum_bgm'));
      	$this->assign('myalbum_copyright',C('myalbum_copyright'));
    	$this->display();
    }
}