<?php
namespace app\admin\controller;
use \think\Validate;
use \think\captcha\Captcha;
class Admin extends \think\Controller
{
    public function add()
    {
       return view('add');
    }

    public function insert()
    {
    	$admin=input('post.admin','','strip_tags');
        $num=input('post.num');
        $psw=input('post.psw');
        $repsw=input('post.repsw');

        $file = request()->file('pic');
    
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $pic_src='/uploads/'.$info->getSaveName();
                echo "<img src='{$pic_src}'>";
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }else{
            $pic_src='/static/admin/images/jiaxiao.jpg';
        }

        $data =[
            'admin' =>$admin,
            'num' =>$num,
            'psw' =>$psw,
            'repsw' =>$repsw,
        ];

        $validate = Validate('User');
        $result   = $validate->check($data);
        if(! $result){
            $this->error($validate->getError());
        }

        $data=[
            'admin' =>$admin,
            'num' =>$num,
            'psw' =>md5($psw),
            'pic' =>$pic_src
        ];
        $ret=db('admins')->insert($data);
        if($ret==false)
        {
            $this->error("添加失败！");
        }
        $this->success("添加成功！","admin/admin/alist");


    }

    public function update()
    {
        $id=input('post.id');
        $admin=input('post.admin','','strip_tags');
        $num=input('post.num');
        $psw=input('post.psw');
        $repsw=input('post.repsw');
        $data=[
                    'admin' => $admin,
                    'psw' => $psw,
                    'repsw' => $repsw
            ];
        if($psw!=""){
            $validate = Validate('Userupdate');
            $result   = $validate->check($data);
            if(! $result){
                $this->error($validate->getError());
            }
        }
       
        $file = request()->file('pic');
    
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $pic_src='/uploads/'.$info->getSaveName();
                $data['pic']=$pic_src;
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        if($psw!=""){
            $data['psw']=md5($psw);
        }
        unset($data['repsw']);

       
        $ret=db('admins')->where("id={$id}")->update($data);
        if($ret==false)
        {
            $this->error("修改失败！");
        }
        $this->success("修改成功！","admin/admin/alist");


    }

    public function edit()
    {
        $id=input('get.id');
        $ret=db('admins')->where("id={$id}")->find();
        $this->assign('ret', $ret);
        return view('edit');
    }

    public function del()
    {
        $id=input('get.id');
        $ret=db('admins')->where("id={$id}")->delete();
        if ($ret==false) {
            $this->error("删除失败！");
        }
        $this->success("删除成功!");
    }

    public function alist()
    {
        $alist = db('admins')->paginate(2);
        $pageHtml = $alist->render();
        $this->assign('alist', $alist);
        $this->assign('pageHtml', $pageHtml);
        return view('list');
    }
}
