<?php


namespace app\test\controller;
use app\model\Comment as CommentModel;

class Comment
{
    //发布评论接口
    public function sendcomment ()
    {
        $info = $_POST;
        $data = [
          'nid'     =>      $info['nid'],
          'uid'     =>      $info['uid'],
          'comment' =>      $info['comment'],
        ];

        //判断评论内容是否为空，
        if($data['comment']==null)
        {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg']        = '评论内容不得为空';
            return json($return_data);
        }

        //插入评论表并返回数据
        $comment = new CommentModel();
        $flag = $comment->save($data);
        if($flag){
            $return_data['error_code'] = 0;
            $return_data['msg']        = '发帖成功';
            $return_data['data']       = CommentModel::get($comment->id);
            return json($return_data);
        }
    }
}