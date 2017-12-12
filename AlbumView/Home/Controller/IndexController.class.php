<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
  	public function index(){
		//查询网站基础内容
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_icon',$basicinfo[myalbum_icon]);
		$this -> assign('myalbum_logo',$basicinfo[myalbum_logo]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		//var_dump($info); //用于输出测试
		//查询数据库中页面导航部分
		$this -> navibar = M('myalbum_navi') -> order("nid asc") -> select();
		//查询数据库中相册封面部分
		$this -> cover = M('myalbum_cover') -> where("open='%d'",1) -> order("cid asc") -> select();
		$this -> display();
    }
}