<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $result = array(
            'code'  =>  200,
            'message'   =>  '系统API运行正常！使用方法参见系统技术文档。',
            'requestId' =>  date('YmdHis',time())
        );
        $this->ajaxReturn($result,'xml');
    }
}