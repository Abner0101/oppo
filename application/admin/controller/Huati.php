<?php
namespace app\admin\controller;
class Huati extends \think\Controller
{
	public function add()
	{
		return view("huatiadd");
	}
	public function huatilist()
	{
		$alist = db('huati')->order('htid desc')->paginate(2);
        $pageHtml = $alist->render();
        $this->assign('alist', $alist);
        $this->assign('pageHtml', $pageHtml);
		return view("huatilist");
	}

	public function del(){
		$id=input('get.id');
		$ret=db('huati')->where("id={$id}")->delete();
		if ($ret==false) {
			$this->error("删除失败！");
		}
		$this->success("已删除该话题!");
	}

	public function edit(){
		$id=input('get.id');
        $ret=db('huati')->where("id={$id}")->find();
        $this->assign('ret',$ret);
        return view('huatiedit');
	}

    public function update(){
        $id=input('post.id');
        $htname=input('post.htname');
        $htid=input('post.htid');
        $htdesc=input('post.htdesc');

        $file = request()->file('htpic');
    
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/upload_ht');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $htsrc='/uploads/upload_ht/'.$info->getSaveName();
                echo "<img src='{$htsrc}' style='width:200px;'>";
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

        $data=[
            'htname' => $htname,
            'htid' => $htid,
            'htdesc' => $htdesc,
            'htpic' => $htsrc
        ];
        $ret=db('huati')->where("id={$id}")->update($data);
        if ($ret==false) {
            $this->error("修改失败!");
        }
        $this->success("修改成功！","/admin/huati/huatilist");
    }

	public function insert(){
		$htname=input('post.htname');
		$htid=input('post.htid');
		$htdesc=input('post.htdesc');

		$file = request()->file('htpic');
    
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/upload_ht');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $htsrc='/uploads/upload_ht/'.$info->getSaveName();
                echo "<img src='{$htsrc}'>";
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

        $data=[
        	'htname' => $htname,
        	'htid' => $htid,
        	'htdesc' => $htdesc,
        	'htpic' => $htsrc
        ];
        $ret=db('huati')->insert($data);
        if ($ret==false) {
        	$this->error("添加失败!");
        }
        $this->success("添加成功！");
	}


}
?>