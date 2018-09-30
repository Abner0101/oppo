<?php
namespace app\home\controller;
use \think\Validate;
use \think\captcha\Captcha;
class Register extends \think\Controller
{
    public function index()
    {
      return view('register');
    }
    
    public function sms()
    {
        $tell   = input('post.tell','','htmlspecialchars');
        //创蓝
        $api = "http://sms.quweiziyuan.cn/sms.php";
        $random = mt_rand(100000, 999999);

        $data = array(
            'key' => 'wein07699',
            'tell' => "{$tell}",
            'code' => "{$random}"
        );
        session('tell_code', $random);
        session('tell', $tell);

        $ret = post($api, $data);
        echo $ret;
        $result = [
            'status' => true,
            'msg' => 'ok',
            'code' => $random
        ];
        echo json_encode($result);
    }

    public function insert(){
    	$phone=input('post.phonenum','','strip_tags');
    	$pwd=input('post.password');
    	$repwd=input('post.repassword');
        $verify=input('post.verify');
        $code=input('post.code');

        $data = [
            'phone' =>$phone,
            'pwd' => $pwd,
            'repwd' => $repwd,
            'verify' => $verify
        ];
        if (session('tell') != $phone) {
            $this->error("注册号码与发送短信验证码号码不一致");
        }
        if (session('tell_code') != $code) {
            $this->error("验证码错误！");
        }


        $validate = Validate('User');
        $result   = $validate->check($data);
        if(! $result){
            $this->error($validate->getError());
        }

    	
    	$data=[
            'phone'=>$phone,
            'passWord'=>md5($pwd)
    	];

    	$ret=db('users')->insert($data);
    	if($ret==false)
    	{
    		$this->error("注册失败！");
    	}
    	$this->success("注册成功！","home/login/index");
    }

    public function verify()
    {
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,    
            // 验证码位数
            'length'      =>    4,   
            // 关闭验证码杂点
            'useNoise'    =>    false, 
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}