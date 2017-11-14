<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends APIController {

	/**
	 * API控制器默认入口回显
	 */
    public function index(){
        $result = array(
            'code'  =>  200,
            'message'   =>  '系统API运行正常！使用方法参见系统技术文档。',
            'requestId' =>  date('YmdHis',time())
        );
        APIController::api($result);
	}
	
	/**
	 * 用户登录过程
	 */
    public function login(){
        $user = I('param.user','','htmlspecialchars');
        $pswd = I('param.pswd','','htmlspecialchars');
        if ($user == '' || $pswd == '') {
            $result = array(
                'code'  =>  405,
                'message'   =>  '用户名或密码不能为空，请重试。',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
        }
        $users = M('myalbum_users');
		$userinfo = $users->where('username="'.$user.'" AND userpwd="'.sha1($pswd).'"')->select();
		if ($userinfo) {
			session("myalbum_token",$userinfo[0]["usertoken"]);
			cookie("myalbum_token",$userinfo[0]["usertoken"]);
			session("myalbum_user",$userinfo[0]["username"]);
			session("myalbum_email",$userinfo[0]["email"]);
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
				'message'   =>  '用户名或密码不正确！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
	}
	
	/**
	 * 用户信息的注册登记
	 */
	public function register() {
		$user = I('param.user','','htmlspecialchars');
		$pswd = I('param.pswd','','htmlspecialchars');
		$mail = I('param.mail','','htmlspecialchars');
		if ($user == '' || $pswd == '' || $mail == '') {
			$result = array(
                'code'  =>  405,
                'message'   =>  '用户信息不完整，无法继续注册。请重试！',
                'requestId' =>  date('YmdHis',time())
            );
            APIController::api($result);
		}
		$users = M('myalbum_users');
		$ucheck = $users->where('username="'.$user.'"')->select();
		if ($ucheck) {
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
		$op_result = $users->data($udata)->add();
		if ($op_result) {
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

	/**
	 * 用户会话的退出
	 */
	public function logout() {
		session("myalbum_token",null);
		session("myalbum_user",null);
		session("myalbum_email",null);
		cookie("myalbum_token",null);
		$result = array(
			'code'  =>  200,
			'message'   =>  '操作成功结束，当前用户会话已从本机注销！',
			'requestId' =>  date('YmdHis',time())
		);
		APIController::api($result);
	}

	/**
	 * 后台操作者API主入口
	 */
	public function operate() {
		switch(I('mod','','htmlspecialchars')) {
			case "baseinfo":
				if (I('type','','htmlspecialchars') != 'write') {
					$baseinfo = M('myalbum_basicinfo');
					$baseinfo = $baseinfo->select();
					APIController::api($baseinfo);
				}
				else {
					self::verifyTOKEN(I('token','','htmlspecialchars')); //写操作验证TOKEN
					$data = @$_POST['data'];
					self::updateBaseInformation($data);
				}
			break;
			case "navinfo":
				if (I('type','','htmlspecialchars') != 'write') {
					$navinfo = M('myalbum_navi');
					$navinfo = $navinfo->select();
					APIController::api($navinfo);
				}
				else {
					self::verifyTOKEN(I('token','','htmlspecialchars')); //写操作验证TOKEN
					$m_nid = @$_POST['nid'];
					$data = @$_POST['data'];
					self::updateNavigation($m_nid, $data);
				}
			break;
			case "userinfo":
				if(I('type','','htmlspecialchars') != 'write') {
					$userinfo = M('myalbum_users');
					$userinfo = $userinfo->select();
					APIController::api($userinfo);
				}
				else {
					self::verifyTOKEN(I('token','','htmlspecialchars')); //写操作验证TOKEN
					$m_uid = @$_POST['uid'];
					$data = @$_POST['data'];
					self::updateUsers($m_uid, $data);
				}
			break;
			case "coverinfo":
				if(I('type','','htmlspecialchars') != 'write'){
					$coverinfo = M('myalbum_cover');
					$coverinfo = $coverinfo->select();
					APIController::api(coverinfo);
				}
				else {
					self::verifyTOKEN(I('token','','htmlspecialchars'));
					$m_cid = @$_POST['cid'];
					$data = @$_POST['data'];
					self::updateCovers($m_cid, $data);
				}
			break;
			default:
				$result = array(
					'code'  =>  405,
					'message'   =>  '无效的操作类，请确认是否传入了合法的mod。',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			break;
		}
	}

	/**
	 * 网站基础信息更新
	 * @param string $data JSON数据字串
	 * @return string XML处理结果
	 */
	private function updateBaseInformation($data) {
		if($data == null || $data == '') {
			$result = array(
				'code'  =>  -1,
				'message'   =>  '没有传入任何配置参数，本次配置更新操作已被取消。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
		else {
			$data_array = json_decode($data);
			if($data_array->name == null || $data_array->nickname == null || $data_array->icon == null || $data_array->logo == null || $data_array->saying == null || $data_array->author == null || $data_array->copyright == null) {
				$result = array(
					'code'  =>  -2,
					'message'   =>  '配置参数字符串无效，请联系站点管理员获取正确的配置信息格式。',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			$baseinfo = M('myalbum_basicinfo');
			$basedata = array(
				'myalbum_name'	=>	$data_array->name,
				'myalbum_nickname'	=>	$data_array->nickname,
				'myalbum_icon'	=>	$data_array->icon,
				'myalbum_logo'	=>	$data_array->logo,
				'myalbum_saying'	=>	$data_array->saying,
				'myalbum_author'	=>	$data_array->author,
				'myalbum_copyright'	=>	$data_array->copyright
			);
			$sitename = $baseinfo->select()[0]['myalbum_name'];
			$up_result = $baseinfo->where('myalbum_name="'.$sitename.'"')->save($basedata);
			if ($up_result) {
				$result = array(
					'code'  =>  200,
					'message'   =>  '数据保存完毕，操作成功结束！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			else {
				$result = array(
					'code'  =>  500,
					'message'   =>  '数据写入失败，可能是您没有修改任何内容或系统忙碌。如果此情况多次出现，请联系系统管理员！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
		}
	}

	/**
	 * 页面导航更新
	 * @param string $nid 导航ID
	 * @param string $data JSON数据字串
	 * @return string XML处理结果
	 */
	private function updateNavigation($nid = null, $data = null) {
		if ($nid == null || $nid == '') {
			if($data == null || $data == '') {
				$result = array(
					'code'  =>  -1,
					'message'   =>  '没有传入任何配置参数，本次导航新增操作已被取消。',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			$data_array = json_decode($data);
			if($data_array->m_navi == null || $data_array->m_link == null) {
				$result = array(
					'code'  =>  -2,
					'message'   =>  '配置参数字符串无效，请联系站点管理员获取正确的配置信息格式。',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			$navinfo = M('myalbum_navi');
			$navidata = array(
				'navi'	=>	$data_array->m_navi,
				'link'	=>	$data_array->m_link
			);
			$op_result = $navinfo->data($navidata)->add();
			if ($op_result) {
				$result = array(
					'code'  =>  200,
					'message'   =>  '数据保存完毕，操作成功结束！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			else {
				$result = array(
					'code'  =>  500,
					'message'   =>  '数据写入失败，可能是您没有修改任何内容或系统忙碌。如果此情况多次出现，请联系系统管理员！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
		}
		else {
			if($data == null || $data == '') {
				$result = array(
					'code'  =>  -1,
					'message'   =>  '没有传入任何配置参数，本次配置更新操作已被取消。',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			else {
				$data_array = json_decode($data);
				if($data_array->m_navi == null || $data_array->m_link == null) {
					$result = array(
						'code'  =>  -2,
						'message'   =>  '配置参数字符串无效，请联系站点管理员获取正确的配置信息格式。',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				$navinfo = M('myalbum_navi');
				$navidata = array(
					'nid'	=>	$nid,
					'navi'	=>	$data_array->m_navi,
					'link'	=>	$data_array->m_link
				);
				$up_result = $navinfo->where('nid='.$m_nid.'')->save($navidata);
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '数据保存完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					$result = array(
						'code'  =>  500,
						'message'   =>  '数据写入失败，可能是您没有修改任何内容或系统忙碌。如果此情况多次出现，请联系系统管理员！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
			}
		}
	}

	/**
	 * Token合法性验证
	 * @param string $s_token 会话token
	 * @return string XML处理结果
	 */
	private function verifyTOKEN($s_token = null) {
		if ($s_token == null) {
			$result = array(
				'code'  =>  401,
				'message'   =>  '会话密钥非法，请不要恶意攻击本系统。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
		else if ($s_token != session('myalbum_token')) {
			$result = array(
				'code'  =>  403,
				'message'   =>  '无效的密钥，可能您本次会话已经过期。请尝试重新登录！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
	}
}