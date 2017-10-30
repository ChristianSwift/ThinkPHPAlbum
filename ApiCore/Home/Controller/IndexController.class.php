<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends APIController {
    public function index(){
        $result = array(
            'code'  =>  200,
            'message'   =>  '系统API运行正常！使用方法参见系统技术文档。',
            'requestId' =>  date('YmdHis',time())
        );
        APIController::api($result);
    }
    public function login(){
        $user = I('param.user','','htmlspecialchars');
        $pswd = I('param.pswd','','htmlspecialchars');
        if($user == '' || $pswd == '') {
            $result = array(
                'code'  =>  405,
                'message'   =>  '用户名或密码不能为空，请重试。',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
        }
        $users = M('myalbum_users');
		$userinfo = $users -> where('username="'.$user.'" AND userpwd="'.sha1($pswd).'"') -> select();
		if($userinfo) {
			$result = array(
				'code'  =>  200,
				'message'   =>  '用户登录成功！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
		else {
			$result = array(
				'code'  =>  403,
				'message'   =>  '用户登录失败，用户名或密码不正确！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
    }
	public function register() {
		$user = I('param.user','','htmlspecialchars');
		$pswd = I('param.pswd','','htmlspecialchars');
		$mail = I('param.mail','','htmlspecialchars');
		if($user == '' || $pswd == '' || $mail == '') {
			$result = array(
                'code'  =>  405,
                'message'   =>  '用户信息不完整，无法继续注册。请重试！',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
		}
		$users = M('myalbum_users');
		$ucheck = $users -> where('username="'.$user.'"') -> select();
		if($ucheck) {
			$result = array(
                'code'  =>  502,
                'message'   =>  '已有相同用户名的账号，请换一个更有创意的名字吧。',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
		}
		$udata = array(
			'username'	=>	$user,
			'userpwd'	=>	sha1($pswd),
			'usertoken'	=>	sha1($user.$pswd),
			'email'	=>	$mail
		);
		$op_result = $users -> data($udata) -> add();
		if($op_result) {
			$result = array(
                'code'  =>  200,
                'message'   =>  '恭喜您，用户注册成功！',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
		}
		else {
			$result = array(
                'code'  =>  500,
                'message'   =>  '用户注册失败！后台数据库处于忙碌状态，请稍后再次尝试。',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
		}
	}
}