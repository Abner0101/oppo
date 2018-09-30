<?php
namespace app\home\controller;

class Index extends \think\Controller
{
    public function index()
    {
		$ret=db('note')->alias('a')->join('users b','a.uid=b.id')->field('a.*,b.username,b.toupic')->select();
		$data=[];
		foreach ($ret as $val) {
			$imgs=json_decode($val['img_src'],true);
			if ($imgs==NULL) {
				$val['img_src']=array($val['img_src']);
			}else{
				$val['img_src']=$imgs;
			}
			$data[]=$val;
		}
		$this->assign('topiclist', $data);
       return view('index');
    }
}
