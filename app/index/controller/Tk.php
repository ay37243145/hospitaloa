<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Tk extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function tkjx()
    {
        return View::fetch();
    }

    public function zccl()
    {
        return View::fetch();
    }

    //款列表
    public function k_list(){
        $limit = Request::param('limit');
//        $limits = Request::param('limits');
        $list['data'] = Db::name('k_list')->order('id', 'asc')->paginate($limit);
        if($list){
            $list['code'] = 0;
            $list['msg'] = '请求成功';
        }else{
            $list['code'] = 1;
            $list['msg'] = '请求失败';
        }
        return json($list);
    }
}

