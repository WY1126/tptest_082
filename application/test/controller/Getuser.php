<?php


namespace app\test\controller;

use app\model\Comment as CommentModel;
use app\model\Coterie as CoterieModel;
use app\model\Note as NoteModel;
use app\model\User as UserModel;
use think\Controller;
use think\Db;

class Getuser extends Controller
{
    public function index()
    {
//        $user = Db::name('tp_user')->select();
        $user = UserModel::select();
        return json($user);
    }
}