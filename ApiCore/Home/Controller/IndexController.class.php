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
		//检测用户数据库是否被初始化
		$users = M('myalbum_users');
		$userinfo = $users->select();
		if($userinfo != null) { //如果已被初始化，则关闭匿名注册功能。仅放行有token的请求继续进行注册！
			self::verifyTOKEN(I('token','','htmlspecialchars'));
		}
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
					self::verifyTOKEN(I('token','','htmlspecialchars'));
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
					self::verifyTOKEN(I('token','','htmlspecialchars'));
					$m_nid = @$_POST['nid'];
					$data = @$_POST['data'];
					self::updateNavigation($m_nid, $data);
				}
			break;
			case "userinfo":
				//由于用户数据的特殊性，这里读写操作均需要token认证。
				self::verifyTOKEN(I('token','','htmlspecialchars'));
				if(I('type','','htmlspecialchars') != 'write') {
					$userinfo = M('myalbum_users');
					$userinfo = $userinfo->select();
					APIController::api($userinfo);
				}
				else {
					$m_uid = @$_POST['uid'];
					$data = @$_POST['data'];
					self::updateUsers($m_uid, $data);
				}
			break;
			case "coverinfo":
				if(I('type','','htmlspecialchars') != 'write'){
					$coverinfo = M('myalbum_cover');
					$coverinfo = $coverinfo->select();
					APIController::api($coverinfo);
				}
				else {
					self::verifyTOKEN(I('token','','htmlspecialchars'));
					$m_cid = @$_POST['cid'];
					$data = @$_POST['data'];
					self::updateCovers($m_cid, $data);
				}
			break;
			case "UploadPic":
			if($_SERVER['REQUEST_METHOD']!="POST") {
				$result = array(
					'code'  =>  405,
					'message'   =>  '该接口必须使用POST方式请求，请重试。',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			self::verifyTOKEN(I('token','','htmlspecialchars'));
			self::PicUploader();
			break;
			case "picinfo":
				if(I('type','','htmlspecialchars') != 'write'){
					$picinfo = M('myalbum_photo');
					$picinfo = $picinfo->select();
					APIController::api($picinfo);
				}
				else {
					self::verifyTOKEN(I('token','','htmlspecialchars'));
					$m_pid = @$_POST['pid'];
					$data = @$_POST['data'];
					self::updatePhoto($m_pid, $data);
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
			self::showError(-1);
		}
		else {
			$data_array = json_decode($data);
			if($data_array->name == null || $data_array->nickname == null || $data_array->icon == null || $data_array->logo == null || $data_array->saying == null || $data_array->author == null || $data_array->copyright == null) {
				self::showError(-2);
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
				self::showError(500);
			}
		}
	}

	/**
	 * 相片上传接口
	 * @return null
	 */
	private function PicUploader() {
		if (!isset($_POST['add_photoname']) || !isset($_POST['add_photoinst']) || !isset($_POST['current_cid']) || @$_POST['add_photoname'] == '' || @$_POST['add_photoinst'] == '' || @$_POST['current_cid'] == '') {
			$this->error('抱歉，请先完善必填表单项目后再次尝试提交！');
		}
		$upload = new \Think\Upload();
        $upload->maxSize = 8388608;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Public/';
        $upload->savePath = 'Uploads/';
        $upload->replace = true;
		$upload_result = $upload->upload();
		if (!$upload_result) {
			$this->error($upload->getError());
		}
		$upload_urlpath = $upload->rootPath.$upload_result['add_upload']['savepath'].$upload_result['add_upload']['savename'];
		$image = new \Think\Image(); 
		$image->open($upload_urlpath);
		$image->thumb(1024, 683)->save($upload_urlpath.'.thumb.jpg');
		$picinfo = M('myalbum_photo');
		$picdata = array(
			'cid'	=>	$_POST['current_cid'],
			'name'	=>	$_POST['add_photoname'],
			'inst'	=>	$_POST['add_photoinst'],
			'preimg'	=>	$upload_urlpath.'.thumb.jpg',
			'bigimg'	=>	$upload_urlpath
		);
		$op_result = $picinfo->data($picdata)->add();
		if ($op_result) {
			$this->success('恭喜您，相片上传成功！');
		}
		else {
			$this->error('抱歉，文件上传成功但数据库写入失败！请与您的服务器提供者联系。');
		}
	}

	/**
	 * 相片更新接口
	 * @param integer $m_pid 相片ID
	 * @param string $data JSON数据字串
	 * @return string XML处理结果
	 */
	private function updatePhoto($m_pid = null, $data = null) {
		if ($m_pid == null || $m_pid == '' || $data == null || $data == '') {
			$result = array(
				'code'  =>  -1,
				'message'   =>  '没有传入任何配置参数，本次相片更新操作已被取消。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
		else if ($data == 'del') {
			$picinfo = M('myalbum_photo');
			$up_result = $picinfo->where('pid='.$m_pid)->delete();
			if ($up_result) {
				$result = array(
					'code'  =>  200,
					'message'   =>  '数据移除完毕，操作成功结束！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			else {
				self::showError(500);
			}
		}
		else {
			$data_array = json_decode($data);
			if ($data_array->m_picname == null || $data_array->m_picinst == null || $data_array->m_preimg == null || $data_array->m_bigimg == null) {
				self::showError(-2);
			}
			$picinfo = M('myalbum_photo');
			$picdata = array(
				'name'	=>	$data_array->m_picname,
				'inst'	=>	$data_array->m_picinst,
				'preimg'	=>	$data_array->m_preimg,
				'bigimg'	=>	$data_array->m_bigimg
			);
			$up_result = $picinfo->where('pid='.$m_pid)->save($picdata);
			if ($up_result) {
				$result = array(
					'code'  =>  200,
					'message'   =>  '数据保存完毕，操作成功结束！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			else {
				self::showError(500);
			}
		}
	}

	/**
	 * 相册管理接口
	 * @param integer $m_cid 相册ID
	 * @param string $data JSON数据字串
	 * @return string XML处理结果
	 */
	private function updateCovers($m_cid = null, $data = null) {
		if ($m_cid == null || $m_cid == '') {
			if($data == null || $data == '') {
				self::showError(-1);
			}
			$data_array = json_decode($data);
			if ($data_array->m_cname == null || $data_array->m_copen == null || $data_array->m_cstyle == null || $data_array->m_cimg == null || $data_array->m_cdetail == null) {
				self::showError(-2);
			}
			$coverinfo = M('myalbum_cover');
			$coverdata = array(
				'style'	=>	$data_array->m_cstyle,
				'open'	=>	$data_array->m_copen,
				'name'	=>	$data_array->m_cname,
				'inst'	=>	$data_array->m_cdetail,
				'coveraddr'	=>	$data_array->m_cimg
			);
			$op_result = $coverinfo->data($coverdata)->add();
			if ($op_result) {
				$result = array(
					'code'  =>  200,
					'message'   =>  '数据保存完毕，操作成功结束！',
					'requestId' =>  date('YmdHis',time())
				);
				APIController::api($result);
			}
			else {
				self::showError(500);
			}
		}
		else {
			if($data == null || $data == '') {
				self::showError(-1);
			}
			else if ($data == 'del') {
				$coverinfo = M('myalbum_cover');
				$up_result = $coverinfo->where('cid='.$m_cid)->delete();
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '数据移除完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					self::showError(500);
				}
			}
			else {
				$data_array = json_decode($data);
				if($data_array->m_cname == null || !isset($data_array->m_copen) || $data_array->m_cstyle == null || $data_array->m_cimg == null || $data_array->m_cdetail == null) {
					self::showError(-2);
				}
				$coverinfo = M('myalbum_cover');
				$coverdata = array(
					'cid'	=>	$m_cid,
					'style'	=>	$data_array->m_cstyle,
					'open'	=>	$data_array->m_copen,
					'name'	=>	$data_array->m_cname,
					'inst'	=>	$data_array->m_cdetail,
					'coveraddr'	=>	$data_array->m_cimg
				);
				$up_result = $coverinfo->where('cid='.$m_cid)->save($coverdata);
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '数据保存完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					self::showError(500);
				}
			}
		}
	}

	/**
	 * 用户信息更新
	 * @param integer $uid 用户ID
	 * @param string $data JSON数据字串
	 * @return string XML处理结果
	 */
	private function updateUsers($uid = null, $data = null) {
		if ($uid == null || $uid == '') {
			$result = array(
				'code'  =>  -1,
				'message'   =>  '没有有效的UID，本次用户信息更新操作已被取消。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
		}
		else {
			if ($data == null || $data == '') {
				self::showError(-1);
			}
			else if ($data == 'del') {
				if ($uid == '1') {
					$result = array(
						'code'  =>  -3,
						'message'   =>  '由于系统核心策略，您不能删除UID为1的初始账户。',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				$usrinfo = M('myalbum_users');
				$up_result = $usrinfo->where('uid='.$uid)->delete();
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '用户移除完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					self::showError(500);
				}
			}
			else {
				$data_array = json_decode($data);
				if($data_array->m_user == null || $data_array->m_mail == null) {
					self::showError(-2);
				}
				if($data_array->m_pswd == null){
					$usrinfo = M('myalbum_users');
					$usrdata = array(
						'uid'	=>	$uid,
						'username'	=>	$data_array->m_user,
						'email'	=>	$data_array->m_mail
					);
				}
				else{
					$usrinfo = M('myalbum_users');
					$usrdata = array(
						'uid'	=>	$uid,
						'username'	=>	$data_array->m_user,
						'userpwd'	=>	sha1($data_array->m_pswd),
						'email'	=>	$data_array->m_mail
					);
				}
				$up_result = $usrinfo->where('uid='.$uid)->save($usrdata);
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '用户数据更新完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					self::showError(500);
				}
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
				self::showError(-1);
			}
			$data_array = json_decode($data);
			if($data_array->m_navi == null || $data_array->m_link == null) {
				self::showError(-2);
			}
			$navinfo = M('myalbum_navi');
			$navidata = array(
				'nid'	=>	$data_array->m_nid,
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
				self::showError(500);
			}
		}
		else {
			if($data == null || $data == '') {
				self::showError(-1);
			}
			else if ($data == 'del') {
				$navinfo = M('myalbum_navi');
				$up_result = $navinfo->where('nid='.$nid)->delete();
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '数据移除完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					self::showError(500);
				}
			}
			else {
				$data_array = json_decode($data);
				if($data_array->m_navi == null || $data_array->m_link == null) {
					self::showError(-2);
				}
				$navinfo = M('myalbum_navi');
				$navidata = array(
					'nid'	=>	$nid,
					'nsid'	=>	$data_array->m_nsid,
					'navi'	=>	$data_array->m_navi,
					'link'	=>	$data_array->m_link
				);
				$up_result = $navinfo->where('nid='.$nid)->save($navidata);
				if ($up_result) {
					$result = array(
						'code'  =>  200,
						'message'   =>  '数据保存完毕，操作成功结束！',
						'requestId' =>  date('YmdHis',time())
					);
					APIController::api($result);
				}
				else {
					self::showError(500);
				}
			}
		}
	}

	/**
	 * 涉及高危交互操作时的Token合法性验证
	 * @param string $s_token 会话token
	 * @return string XML处理结果
	 */
	private function verifyTOKEN($s_token = null) {
		if ($s_token == null) {
			self::showError(401);
		}
		else if ($s_token != session('myalbum_token')) {
			self::showError(403);
		}
	}

	/**
	 * 预定义异常处理
	 * @param integer $code 错误码
	 * @return string XML处理结果
	 */
	private function showError($code = null) {
		switch ($code) {
			case -1:
			$result = array(
				'code'  =>  -1,
				'message'   =>  '没有传入任何配置参数，本次配置更新操作已被取消。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
			break;
			case -2:
			$result = array(
				'code'  =>  -2,
				'message'   =>  '配置参数字符串无效，请联系站点管理员获取正确的配置信息格式。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
			break;
			case 401:
			$result = array(
				'code'  =>  401,
				'message'   =>  '会话密钥非法，请不要恶意攻击本系统。',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
			break;
			case 403:
			$result = array(
				'code'  =>  403,
				'message'   =>  '无效的密钥，可能您本次会话已经过期。请尝试重新登录！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
			break;
			case 500:
			$result = array(
				'code'  =>  500,
				'message'   =>  '用户移除失败，可能系统处于忙碌状态或数据库处于只读封禁模式。如果此情况多次出现，请联系系统管理员！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
			break;
			default:
			$result = array(
				'code'  =>  null,
				'message'   =>  '未定义的程序异常，请联系管理员！',
				'requestId' =>  date('YmdHis',time())
			);
			APIController::api($result);
			break;
		}
	}
}