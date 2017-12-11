<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends WebAuthorityController {
  	public function index(){
		//查询网站基础内容
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
		$this -> assign('myalbum_icon',$basicinfo[myalbum_icon]);
		$this -> assign('myalbum_logo',$basicinfo[myalbum_logo]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		$this -> assign('session_name',session('myalbum_user'));
		$this -> assign('session_id',session('myalbum_token'));
		$this -> assign('session_avatar',getGravatar(session('myalbum_email')));
		//trace(getGravatar(session('myalbum-email')),'提示1');
		$this -> display();
	}
	public function navi(){
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		$this -> assign('session_name',session('myalbum_user'));
		$this -> assign('session_id',session('myalbum_token'));
		$this -> assign('session_avatar',getGravatar(session('myalbum_email')));
		//查询数据库中页面导航部分
		$this -> navibar = M('myalbum_navi') -> order("nid asc") -> order("nsid asc") -> select();
		$this -> display('navi');
	}
	public function user(){
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		$this -> assign('session_name',session('myalbum_user'));
		$this -> assign('session_id',session('myalbum_token'));
		$this -> assign('session_avatar',getGravatar(session('myalbum_email')));
		//查询数据库中页面导航部分
		$this -> userlist = M('myalbum_users') -> order("uid asc") -> select();
		$this -> display('user');
	}
	public function album(){
		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
      	$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
      	$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
      	$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		$this -> assign('session_name',session('myalbum_user'));
		$this -> assign('session_id',session('myalbum_token'));
		$this -> assign('session_avatar',getGravatar(session('myalbum_email')));
		//查询数据库中页面导航部分
		$this -> coverlist = M('myalbum_cover') -> order("cid asc") -> select();
		$this -> display('album');
	}
	public function photo(){
		$cid = I('get.cid','','/^[0-9]*$/');

		$basicinfo = M('myalbum_basicinfo');
		$basicinfo = $basicinfo->select();
		$basicinfo = $basicinfo[0];
		$this -> assign('myalbum_name',$basicinfo[myalbum_name]);
		$this -> assign('myalbum_nickname',$basicinfo[myalbum_nickname]);
		$this -> assign('myalbum_saying',$basicinfo[myalbum_saying]);
		$this -> assign('myalbum_author',$basicinfo[myalbum_author]);
		$this -> assign('myalbum_copyright',$basicinfo[myalbum_copyright]);
		$this -> assign('myalbum_thisyear',date('Y'));
		$this -> assign('session_name',session('myalbum_user'));
		$this -> assign('session_id',session('myalbum_token'));
		$this -> assign('session_avatar',getGravatar(session('myalbum_email')));
		$this -> assign('myalbum_cid',$cid);
		
		if($cid != ''){
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
		}
		else{
			//查询数据库中相册封面部分
			$this -> assign('cover_name','所有相册');
			$this -> assign('cover_inst','');
			//查询数据库中当前相册的所有内容
			$content = M('myalbum_photo');
			$content = $content->select();
			$this -> assign('content',$content);
		}
		$this -> display('photo');
	}
}