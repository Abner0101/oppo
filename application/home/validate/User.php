<?php
namespace app\home\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
	    'phone'   => 'require|length:11|unique:users,phone',
	    'pwd'	 => 'require|min:6',
	    'repwd'  => 'require|confirm:pwd',
	    'verify' => 'require|captcha'
	];
	 protected $message = [
		    'phone.require' => '手机号码必须填写',
		    'phone.length'  => '手机号长度必须为11位',
		    'phone.unique'  => '手机号已经被注册',
		    'pwd.require'  => '密码必须填写',
		    'pwd.min'		=> '密码长度不能小于6位',
		    'repwd.require'  => '确认密码必须填写',
		    'repwd.confirm'  => '两次密码输入不一致',
		    'verify.require' =>'请输入验证码！',
		    'verify.captcha'  => '验证码错误'
		    
		];

}