<?php
namespace app\home\controller;
class Common extends \think\Controller
{
	public function _initialize()
	{
		if(session('user_id') == NULL || session('tell') == NULL){
			$this->error("未登录",'home/login/index',0);
		}else{
			$data=[
				'tell'=>session('tell'),
				'exit'=>"退出"
			];
			$this->assign('alist', $data);
			return view('header');
		}
	} 
}
?>