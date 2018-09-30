<?php
namespace app\home\controller;
class Comment extends \think\Controller
{
	public function add()
	{
        if ( session('user_id') == NULL )
        {
            $this->error("未登录",'home/login/index',0);
        }
        $uid=session('user_id');
        $noteid=input('post.noteid');
        $content=input('post.content');
        if ( $noteid == "" )
        {
            $this->error("参数错误!", "", 1);
        }
        if ( $content == "" )
        {
            $this->error("评论内容不能为空!", "", 2);
        }
        $data=[
            'uid'=>$uid,
            'noteid'=>$noteid,
            'content'=>$content
        ];
        $ret=db('comment')->insert($data);
        if ($ret==false) {
            $this->error("评论失败！");
        }
        $this->success("评论成功");

	}
    public function comlist()
    {
        $noteid=input('get.noteid');
        $pageno=input('get.pageno',1);

        $pagesize=1;
        $left = ($pageno-1)*$pagesize;
        $list=db('comment')->alias('a')->join('users b','a.uid=b.id')->where("a.noteid={$noteid}")->field('a.*,b.username,b.toupic')->limit("{$left},{$pagesize}")->order("a.id desc")->select();
        if ( empty($list) )
        {
            $this->error("没有了","", 3);
        }

        $this->assign('list',$list);
        $html=$this->fetch('comment_list');
        $this->success("成功","",$html);

    }

    public function readd()
    {

        if ( session('user_id') == NULL )
        {
            $this->error("未登录",'home/login/index',0);
        }
        $uid=session('user_id');
        $noteid=input('post.noteid');
        $content=input('post.content');
        $comid=input('post.comid');
        if ( $noteid == "" )
        {
            $this->error("参数错误!", "", 1);
        }
        if ( $content == "" )
        {
            $this->error("评论内容不能为空!", "", 2);
        }
        if ($comid == "") {
            $this->error("参数错误！");
        }
        $data=[
            'uid'=>$uid,
            'noteid'=>$noteid,
            're_content'=>$content,
            'comid'=>$comid
        ];
        $ret=db('comment_re')->insert($data);
        if ($ret==false) {
            $this->error("评论失败！");
        }
        $this->success("评论成功");
    }

    public function recomlist()
    {
        $comid=input('get.comid');
        $olist=db('comment_re')->alias('a')->join('users b','a.uid=b.id')->where("a.comid={$comid}")->field('a.*,b.username,b.toupic')->select();
        if ( empty($olist) )
        {
            $this->error("没有其他评论");
        }


        
        $this->assign('olist',$olist);
        $html=$this->fetch('comment');
        $this->success("成功","",$html);

    }

}

?>