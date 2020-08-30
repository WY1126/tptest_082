<?php


namespace app\test\controller;

use app\model\Comment as CommentModel;
use app\model\Coterie as CoterieModel;
use app\model\Note as NoteModel;
use app\model\User;
use app\model\User as UserModel;
use think\Controller;
use think\Db;

class Getuser extends Controller
{
    //用户注册接口
    public function login()
    {
        $user = $_POST;
        $uname      =       $user['uname'];
        $sid        =       $user['sid'];
        $password   =       $user['password'];
        $avatar     =       $user['avatar'];
        //检查参数不足情况
        if(!$uname)
        {
            return json([
               "error_code"     =>      1,
               "msg"            =>      "参数不足unmae",
            ]);
        }
        //两次密码不一致
        if($user['password']!==$user['password_again'])
        {
            return json([
                "error_code"     =>      2,
                "msg"            =>      "两次密码不一致",
            ]);
        }
        //学号已被注册
        if(User::where('sid',$sid)->find()!=null)
        {
            return json([
                "error_code"     =>      3,
                "msg"            =>      "学号已被注册",
            ]);
        }
        //成功注册 加入数据库
        $data = [
          'uname'       =>      $uname,
          'sid'         =>      $sid,
        //密码 md5加密
          'password'    =>      md5($password),
          'avatar'      =>      $avatar,
        ];
        $userinfo = new UserModel();
        $flag = $userinfo->save($data);
        if($flag)
        {
            return json([
                "error_code"     =>      0,
                "msg"            =>      "注册成功",
                "data"           =>       [
                    'id'        =>      $userinfo->id,
                    'uname'     =>      $userinfo->uname,
                    'sid'       =>      $userinfo->sid,
                    'avatar'    =>      $userinfo->avatar,
                ]
            ]);
        }
    }

    //用户登录接口
    public function logon()
    {
        $sid   = request()->param('sid');
        $pwd   = md5(request()->param('password'));
        $key   = [
            'sid'     =>      $sid,
            'password'  =>      $pwd,
        ];
        $userinfo = UserModel::where($key)->find();
//        return json($userinfo);
        if(!$userinfo)
        {
            return json([
                "error_code"     =>      1,
                "msg"            =>      "用户名或密码错误",
            ]);
        }
        else
        {
            return json([
                "error_code"     =>      0,
                "msg"            =>      "登陆成功",
                "data"           =>      $userinfo,
            ]);
        }
    }
}