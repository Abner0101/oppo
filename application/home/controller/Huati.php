<?php
namespace app\home\controller;
class Huati extends \think\Controller
{
	public function index(){
		$ret=db('huati')->order('htid desc')->select();
        $this->assign('topic', $ret);
		return view('topic');
	}
}
