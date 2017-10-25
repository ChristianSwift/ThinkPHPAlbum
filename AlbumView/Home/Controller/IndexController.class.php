<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
  	public function index(){
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));

		//var_dump($info);;用于输出测试
		$this -> navibar = M('myalbum_navi') -> select();

		$this -> display();
    }
}