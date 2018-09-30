<?php
namespace app\home\validate;

use think\Validate;

class Valinote extends Validate
{
    protected $rule = [
	  'title' => 'require',
	  'huati' => 'require',
	  'img_src' => 'require',
	  'content' => 'require|min:20'
	];
	 protected $message = [
		    'title.require' => '必须填写标题',
		    'huati.require' => '请选择话题',
		    'img_src.require' => '请上传主题图片',
		    'content.require' => '必须输入正文内容',
		    'content.min' =>'正文内容不能少于20字'	    
		];

}