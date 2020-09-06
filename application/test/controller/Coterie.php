<?php


namespace app\test\controller;

use app\model\Coterie as CoterieModel;
use think\facade\Request;
use app\model\Joincoterie as JoincoterieModel;

class Coterie
{

    //创建圈子
    public function createcoterie ()
    {
        $coterieinfo = $_POST;
        $data = [
          'cname'       =>      $coterieinfo['cname'],
          'uid'         =>      $coterieinfo['uid'],
          'summary'     =>      $coterieinfo['summary'],
          'avatar'      =>      $coterieinfo['avatar'],
            //将成员数量默认为一，贴子数量默认为0
          'membernum'   =>      1,
          'notenum'     =>      0,
          'create_time' =>      date('Y-m-d H:i:s'),
        ];

        //判断是否有重名圈子
        if(CoterieModel::where('cname',$data['cname'])->find())
        {
            return json([
                'error_code'        =>      1,
                'msg'               =>      "存在重名圈子",
            ]);
        }
        else
        {
            $coterie = new CoterieModel();
            $flag = $coterie->save($data);
            if($flag)
            {
                return json([
                    'error_code'        =>      0,
                    'msg'               =>      "创建圈子成功",
                    'data'              =>      $coterie,
                ]);
            }
        }
    }

    //解散圈子      权限操作（创建时间必须大于两个月）
    public function deletecoterie ()
    {
        $info = $_POST;
        //查询两个月内 的数据。
        $flag = CoterieModel::whereTime('create_time','-2 month')->where('cid',$info['cid'])->find();
        if($flag!=null)
        {
            return json([
                'error_code'        =>      1,
                'msg'               =>      '圈子创建时间不足两月',
            ]);
        }
        else
        {
            $temp = CoterieModel::where('cid',$info['cid'])->delete();
            if($temp)
            {
                return json([
                    'error_code'        =>      0,
                    'msg'               =>      '解散成功',
                ]);
            }
        }
    }

    //获取我的圈子
    public function getmycoterie ()
    {
        //获取uid 判断是否为空
        $uid = Request::param('uid');
        if($uid==null) {
            return json([
               'error_code'     =>      1,
               'msg'            =>      'uid为空',
            ]);
        }

        //搜索uid的圈子
        $mycoterie = CoterieModel::where('uid',$uid)->select();
        return json($mycoterie);
    }

    //用户加入圈子
    public function joincoterie ()
    {
//        return 'join';
        $info = $_POST;
        $data = [
            'coterie_id'        =>      $info['coterie_id'],
            'user_id'           =>      $info['user_id'],
        ];
        //判断用户是加入圈子还是退出圈子···
        $result = JoincoterieModel::where($data)->find();
        $return_data = array();

        if($result) {
            //修改status 0->1 ,1->0
            $result->status +=1;    $result->status %=2;
            $flag = $result->save();
            if($flag) {
                if($result->status == 0) {
                    $return_data['error_code'] = 0;
                    $return_data['msg']        = '已退出圈子';
                    $return_data['data']       = $result;
                    return json($return_data);
                }
                else {
                    $return_data['error_code'] = 1;
                    $return_data['msg']        = '成功加入圈子';
                    $return_data['data']       = $result;
                    return json($return_data);
                }
            }
            else {
                $return_data['error_code'] = 2;
                $return_data['msg']        = '错误';
                return json($return_data);
            }
        }
        //加入圈子
        $join = new JoincoterieModel();
        $flag = $join->save($data);
        if($flag) {
            $return_data['error_code'] = 1;
            $return_data['msg']        = '成功加入圈子';
            $return_data['data']       = $join;
            return json($return_data);
        }
        else {
            $return_data['error_code'] = 2;
            $return_data['msg']        = '错误';
            return json($return_data);
        }
    }

}