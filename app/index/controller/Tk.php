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

    /*
     * 条款解析
     * */
    public function tkjx()
    {
        return View::fetch();
    }

    /*
     * 支撑材料
     * */
    public function zccl()
    {
        return View::fetch();
    }

    /*
     * 当前条款信息
     * */
    public function tkjx_info($id)
    {
//        View::assign('id',$id);
        $k_list = Db::name('k_list')->find($id);

        $ys_title_count = Db::name('ys_list')->field('title')
                ->where('k_id',$id)->group('title')->count();

        if($ys_title_count==1){
            $c_ys_list = Db::name('ys_list')->where('k_id',$id)->select();
            View::assign('c_ys_list',$c_ys_list);
        }elseif ($ys_title_count==2){
            $c_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'C'])->select();
            $b_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'B'])->select();
            View::assign('c_ys_list',$c_ys_list);
            View::assign('b_ys_list',$b_ys_list);
        }elseif ($ys_title_count==3){
            $c_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'C'])->select();
            $b_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'B'])->select();
            $a_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'A'])->select();
            View::assign('c_ys_list',$c_ys_list);
            View::assign('b_ys_list',$b_ys_list);
            View::assign('a_ys_list',$a_ys_list);
        }

        View::assign('k_list',$k_list);
        View::assign('ys_title_count',$ys_title_count);

        return View::fetch();
    }

    /*
     * 款列表
     * */
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

    public function aa(){
        $count = Db::name('ys_list')->field('title')
            ->where('k_id',1)->group('title')->count();
        $title_list = Db::name('ys_list')->field('title')
            ->where('k_id',1)->group('title')->select();
        foreach ($title_list as $key => $value){
            dump($key);
            dump($value);
        }
    }
}

