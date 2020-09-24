<?php


namespace app\test\controller;

use app\model\Like as LikeModel;
use app\model\Note as NoteModel;
use app\model\Comment as CommentModel;
use app\model\Reply as ReplyModel;
use think\Model;

class Like
{
    public $d=0;

    //创建点赞表
    public function createlike ($temp)
    {
        $like = new LikeModel();
        $flag = $like->save([$temp]);
        return $flag;
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    //用户触发点赞功能
    public function likeme ()
    {
        $info = $_POST;     $likenum=0;
        $temp = [
            'uid'       =>      $info['uid'],
            'like_type' =>      $info['like_type'],
            'like_id'   =>      $info['like_id'],
        ];
        //判断点赞赞表是否存在， 不存在 则创建
        $data = LikeModel::where($temp)->find();
        if(!$data) {
            //创建点赞表
            $like = new LikeModel();
            $f = $like->save($temp);
            if($f) {
                $data = LikeModel::get($like->id);
            }
        }
        $likenum = $this->dolike($data);
        /*
        //点赞帖子
        if($info['like_type']==0) {
            $likenum = $this->likenote($data);
        }
        //点赞评论
        if($info['like_type']==1) {
            $likenum = $this->likecomment($data);
        }
        //点赞回复
        if($info['like_type']==2) {
            $likenum = $this->likereply($data);
        }
        */
        $msg = ['取消点赞','点赞成功'];
        //修改status状态
        $data->status += 1;     $data->status %= 2;
        /*
        if ($data->status) {
            $data->status = 0;
            $msg = '取消点赞';
        } else {
            $data->status = 1;
            $msg = '点赞成功';
        }
        */
        $data->uid = $info['uid'];
        $data->save();

        return json([
           'like_num'       =>      $likenum,
           'status'         =>      $data->status,
            'msg'           =>      $msg[$data->status],
            'like_type'     =>      $data->like_type,
            'like_id'       =>      $data->like_id,
        ]);
    }

    /**
     * 点赞功能返回点赞数
     * @param $data
     * @return int
     */
    public function dolike ($data)
    {
        //根据like_type判断是对那张表点赞
        if($data['like_type'] == 0)
            $temp = NoteModel::get($data['like_id']);
        if($data['like_type'] == 1)
            $temp = CommentModel::get($data['like_id']);
        if($data['like_type'] == 2)
            $temp = ReplyModel::get($data['like_id']);
        //点赞
        if($data['status']==0) {
            $temp->likenum += 1;
        }
        else {
            $temp->likenum -= 1;
        }
        $temp->save();
        return $temp->likenum;
    }
    /*
    //点赞帖子
    public function likenote ($data)
    {
        $note = NoteModel::get($data['like_id']);
        //点赞
        if($data['status']==0) {
            $note->likenum += 1;
        }
        else {
            $note->likenum -= 1;
        }
        $note->save();
        return $note->likenum;
    }

    //点赞评论
    public function likecomment ($data)
    {
        $comment = CommentModel::get($data['like_id']);
        //点赞
        if($data['status']==0) {
            $comment->likenum += 1;
        }
        else {
            $comment->likenum -= 1;
        }
        $comment->save();
        return $comment->likenum;

    }

    //点赞回复
    public function likereply ($data)
    {
        $reply = ReplyModel::get($data['like_id']);
        //点赞
        if($data['status']==0) {
            $reply->likenum += 1;
        }
        else {
            $reply->likenum -= 1;
        }
        $reply->save();
        return $reply->likenum;
    }
    */
}