<?php


namespace app\model;


use think\Model;

class Comment extends Model
{
    //自动获取时间戳
    protected $autoWriteTimestamp = true;
}