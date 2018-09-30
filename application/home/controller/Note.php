<?php
namespace app\home\controller;
use \think\Validate;
use \think\captcha\Captcha;
class Note extends \think\Controller
{
	public function _initialize()
    {
        if ( session('user_id') == NULL )
        {
            $this->error("未登录",'home/login/index');
        }
    }
	public function add()
	{
        $ret=db('huati')->order('htid desc')->select();
        $this->assign('topic', $ret);
		return view('add');
	}

    public function upimg(){

        $file = request()->file('img');
    
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $img_src='/uploads/'.$info->getSaveName();
                echo $img_src;
                // echo "<img src='{$img_src}'>";
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

    }

	public function insert()
    {
    	$title=input('post.title');
        $huati=input('post.huati');
        $content=input('post.content');
        $uid=session('user_id');
        $imgs = $_POST['imgs'];
        $img_src = json_encode($imgs);


        $data=[
            'title'=>$title,
            'huati'=>$huati,
            'img_src'=>$img_src,
            'content'=>$content,
            'uid'=>$uid
        ];

        $validate = Validate('Valinote');
        $result   = $validate->check($data);
        if(! $result){
            $this->error($validate->getError());
        }

        $ret=db('note')->insert($data);
        if ($ret==false) {
            $this->error("发布失败！");
        }
        $this->success("发布成功！");
    }
     


}

?>