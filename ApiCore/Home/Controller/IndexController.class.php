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
        $users -> M('myalbum_users');
		$users -> $users->where("username='%d'",array($user))->select('password');
        exit();
        $result = array(
            'code'  =>  200,
            'message'   =>  '系统API运行1正常！使用方法参见系统技术文档。',
            'requestId' =>  date('YmdHis',time())
        );
        APIController::api($result);
    }
}