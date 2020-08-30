<?php


namespace app\test\controller;
use app\model\Note as NoteModel;
use think\Db;

class Note
{
    public function getnote ()
    {
        return time();
//        dump(time());
//        $data = NoteModel::where('id',30)->find();
//        return $data;
    }
    //创建贴子
    public function createnote ()
    {
        $info = $_POST;
        // 获取表单上传文件
        $imgs = [];
        //接收图片地址的数组
        $files = request()->file('image');
        $this->upload($files,$imgs);
        $data = [
            'cid'       =>      $info['cid'],
            'uid'       =>      $info['uid'],
            'content'   =>      $info['content'],
            'images'    =>      ['imgs'=>$imgs],
        ];
        $noteinfo = new NoteModel();
        $flag = $noteinfo->save($data);
        if($flag) {
            return json((NoteModel::get($noteinfo->id)));
        }
        else {
            echo 'sas'."<hr/>";
        }
    }

    //图片上传
    public function upload($files,&$imgs)
    {
        foreach($files as $file){
            // 移动到框架应用根目录/uploads/ 目录下  验证大小和后缀
            $info = $file->move( '../uploads');
            if($info){
                //向数组添加图片路径
                array_push($imgs,$info->getSaveName());
            }
            else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}