<?php
namespace Home\Controller;
use Think\Controller;
class SubController extends Controller {
  	public function index(){
		//获取传参
		$cid=$_GET['cid'];
		if($cid == ''){
			header("location:index");  
		}
		else{
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
		//查询数据库中相册封面部分
		$cover = M('myalbum_cover');
		$cover = $cover->where("cid='%d'",array($cid))->select();
		$cover = $cover[0];
		$this -> assign('cover_name',$cover[name]);
		$this -> assign('cover_inst',$cover[inst]);
		//查询数据库中当前相册的所有内容
		$content = M('myalbum_photo');
		$content = $content->where("cid='%d'",array($cid))->select();
		$this -> assign('content',$content);
		//var_dump($content);
		$this -> display();
		}
    }
}