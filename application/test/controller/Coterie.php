<?php


namespace app\test\controller;

use app\model\Coterie as CoterieModel;

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
}