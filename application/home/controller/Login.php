<?php
namespace app\home\controller;

class Login extends \think\Controller 
{
	public function index()
	{
		// $data = ['tell' => '188888888', 'username' => 'tangpan'];
		// // 添加单条数据
		// //$ret = db('user')->insert($data); //返回bool
		// //$ret = db('user')->insertGetId($data); //返回插入记录的id

		// //查询一条数据
		// $ret = db('user')->where("id=2")->find();

		// //查询多条数据
		// //$ret = db('user')->where()->order()->limit()->field->group()->select();
		// dump($ret);

		// //删除数据
		// $ret = db('user')->where('id=1')->delete();

		// //更新数据
		// $data = ['tell' => '1388888888', 'username' => 'tangpan'];
		// $ret = db('user')->where("id=2")->update($data);
		// dump($ret);
		$key = "3911964253";

		$redirect_uri = "http://wx.weiyinstudio.com/?backurl=http://www.oppo.org/home/login/webo?";
		$redirect_uri = urlencode($redirect_uri);
		$wb_url = "https://api.weibo.com/oauth2/authorize?client_id={$key}&response_type=code&redirect_uri={$redirect_uri}";

		$wx_redirect_uri = "http://wx.weiyinstudio.com/?backurl=http://www.oppo.org/home/login/wx?";
		$wx_redirect_uri = urlencode($wx_redirect_uri);
		$wx_appid="wxc0f8e88d15289fd7";
		$wx_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$wx_appid}&redirect_uri={$wx_redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
		$this->assign('wx_url',$wx_url);

		$this->assign('wb_url', $wb_url);
		return view('login');
	}

	public function webo(){
		$code=input('get.code');
		$key="3911964253";
		$secret="7ebbd60b716a6163e8108d9e12109de8";
		$redirect_uri = "http://wx.weiyinstudio.com/";
		$url="https://api.weibo.com/oauth2/access_token?client_id={$key}&client_secret={$secret}&grant_type=authorization_code&redirect_uri={$redirect_uri}/&code={$code}";
		$token=post($url,array());
		$token = json_decode($token, true);
		$url="https://api.weibo.com/2/users/show.json?access_token={$token['access_token']}&uid={$token['uid']}";
		$info=get($url);
		$info = json_decode($info, true);

		$ret=db('users')->where("uid='{$token['uid']}'")->find();
		if (empty($ret)) 
		{
			session('we_info',$info);
			$this->redirect("home/login/bind");
		}
		//直接登录
		session('user_id', $ret['id']);
		session('phone', $ret['phone']);
		$this->success("登录成功",'home/index/index');
	}

	public function wx(){
		$code=input('get.code');
		$wx_appid="wxc0f8e88d15289fd7";
		$secret="98adc14c4e2a7f03d5ce94fc6e6eef7d";
		$redirect_uri = "http://wx.weiyinstudio.com/";
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$wx_appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
		$token=post($url,array());
		$token = json_decode($token, true);
		$url="https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid=OPENID&lang=zh_CN";
		$info=get($url);
		$info = json_decode($info, true);

		$ret=db('users')->where("wx_id='{$token['openid']}'")->find();
		if (empty($ret)) 
		{
			session('wexin_info',$info);
			$this->redirect("home/login/bind");
		}
		//直接登录
		session('user_id', $ret['id']);
		session('phone', $ret['phone']);
		$this->success("登录成功",'home/index/index');

	}

	public function bind(){
		
		
		return view('bind');
	}

	public function checkbind(){
		
		
		if (session('wexin_info') !=NULL) {
			$info=session('wexin_info');
		}else{
			$info=session('we_info');
		}
		$phone=input('post.phonenum');
		$code=input('post.code');
		if (session('tell') != $phone) {
            $this->error("注册号码与发送短信验证码号码不一致");
        }
        if (session('tell_code') != $code) {
            $this->error("验证码错误！");
        }

        //检测手机号是否被注册
        $ret=db('users')->where("phone='{$phone}'")->find();
        if (session('wexin_info') !=NULL) {
			if (empty($ret)) 
	       {
		        	$psw=substr($phone, -6);
		        	$data=[
		        		'phone' => $phone,
		        		'wx_id' => $info['openid'],
		        		'username' => $info['nickname'],
		        		'passWord' =>md5($psw)
		        	];
		        	$ret = db('users')->insertGetId($data);
		        	$user_id =$ret;
		     }

	        else
	        {
	        	$data=[
	        		'wx_id' =>$info['openid'],
	        		'username' =>$info['nickname']
	        	];
	        	$ret =db('users')->where("id={$ret['id']}")->update($data);
	        	$user_id=$ret['id'];
	        }
		}
		else{
				if (empty($ret)) {
	        	$psw=substr($phone, -6);
	        	$data=[
	        		'phone' => $phone,
	        		'uid' => $info['id'],
	        		'username' => $info['screen_name'],
	        		'passWord' =>md5($psw)
	        	];
	        	$ret = db('users')->insertGetId($data);
	        	$user_id =$ret;
	        }

	        else
	        {
	        	$data=[
	        		'uid' =>$info['id'],
	        		'username' =>$info['screen_name']
	        	];
	        	$ret =db('users')->where("id={$ret['id']}")->update($data);
	        	$user_id=$ret['id'];
	        }

		}
	       
	  
        
        if ($ret==false) {
        	$this->error("绑定失败！");
        }

        session('user_id',$user_id);
        session('tell',$phone);
        $this->success("绑定成功",'home/index/index');
	}

	public function check()
	{
		$tell = input('post.phone', '', 'strip_tags');	// htmlspecialchars => 转义html代码   , strip_tags => 去掉html标签
		$pwd = input('post.password');

		$pwd = md5($pwd);
		$ret = db('users')->where("phone='{$tell}' and passWord ='{$pwd}'")->find();
		// echo db('user')->getLastSql();
		// die;
		if ( $ret == false )
		{
			$this->error("账号密码不匹配");
		}

		session('user_id', $ret['id']); // $_SESSION['user_id'] = $ret['id']
		session('tell', $ret['phone']);

		$this->success('登录成功','home/index/index');
	}
}