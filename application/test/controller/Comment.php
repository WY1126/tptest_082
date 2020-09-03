<?php


namespace app\test\controller;

use app\model\Comment as CommentModel;
use app\test\controller\Note;
use app\model\Like as LikeModel;
use app\test\controller\Like;

class Comment
{
    //发布评论接口
    public function sendcomment ()
    {

        $info = $_POST;         $imgs = [];
        //接收图片地址的数组
        $files = request()->file('image');
        // 实例化upload()上传图片函数
        $newupload = new Note();
        $newupload->upload($files,$imgs);

        $data = [
          'nid'     =>      $info['nid'],
          'uid'     =>      $info['uid'],
          'comment' =>      $info['comment'],
          'images'  =>      $imgs,
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
            //创建点赞表
//            $like = new Like();
//            $like->createlike(1,$comment->id);

            $return_data['error_code'] = 0;
            $return_data['msg']        = '发帖成功';
            $return_data['data']       = CommentModel::json(['images'])->get($comment->id);
            return json($return_data);
        }
    }
}