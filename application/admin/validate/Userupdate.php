<?php
namespace app\admin\validate;

use think\Validate;

class Userupdate extends Validate
{
    protected $rule = [
	    'admin'   => 'require',
	    'psw'	 => 'require|min:6',
	    'repsw'  => 'require|confirm:psw'
	];
	 protected $message = [
		    'admin.require' => '姓名必须填写',
		    'psw.require'  => '密码必须填写',
		    'psw.min'		=> '密码长度不能小于6位',
		    'repsw.require'  => '确认密码必须填写',
		    'repsw.confirm'  => '两次密码输入不一致'
		];

}