<?php


namespace app\test\controller;
use app\model\Reply as ReplyModel;

class Reply
{
    //发送回复
    public function sendreply ()
    {
        $info = $_POST;
        //判断评论内容是否为空
        if($info['content']==null)
        {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg']        = '评论内容不得为空' ;
            return json($return_data);
        }
        //插入回复表并返回数据
        $data = [
            'comment_id'        =>      $info['comment_id'],
            'reply_id'          =>      $info['reply_id'],
            'reply_type'        =>      $info['reply_type'],
            'content'           =>      $info['content'],
            'from_uid'          =>      $info['from_uid'],
            'to_uid'            =>      $info['to_uid'],
        ];
        $reply = new ReplyModel();
        $flag = $reply->save($data);
        if($flag) {
            $return_data['error_code'] = 0;
            $return_data['msg']        = '回复成功！' ;
            $return_data['data']       = ReplyModel::get($reply->id);
            return json($return_data);
        }
    }
}