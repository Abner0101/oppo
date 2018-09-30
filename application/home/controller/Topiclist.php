<?php
namespace app\home\controller;
class Topiclist extends \think\Controller
{
	public function index(){
		$htid=input('get.id');
		$ret=db('note')->alias('a')->join('users b','a.uid=b.id')->field('a.*,b.username,b.toupic')->where("a.huati={$htid}")->select();
		$data=[];
		foreach ($ret as $val) {
			$imgs=json_decode($val['img_src'],true);
			if ($imgs!=NULL) {
				$val['img_src']=$imgs[0];
			}
			$data[]=$val;
		}
		// dump($ret);die;
		// $data=[];
		// foreach ($ret as $val) {
		// 	$userid=$val['uid'];
		// 	$userinfo=db('users')->where("id={$userid}")->find();
		// 	$val["username"] = $userinfo['username'];
		// 	$val["toupic"]=$userinfo['toupic'];
		// 	$data[]=$val;
		// }
		$this->assign('topiclist', $data);
		return view('topiclist');
	}

	public function detail(){
		$noteid=input('get.id');
		$ret=db('note')->alias('a')->join('users b','a.uid=b.id')->field('a.*,b.username,b.toupic')->where("a.id={$noteid}")->find();
		$ret_comment=db('comment')->where("noteid={$noteid}")->select();
		// dump($ret);die;
		$this->assign('count',$ret_comment);
		$this->assign('alist',$ret);
		return view('detail');
	}
}
?>