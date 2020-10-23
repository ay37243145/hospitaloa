<?php


namespace app\index\controller;


use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Tongzhi extends BaseController
{
    /*
     * 加载公告首页
     * */
    public function index()
    {
        return View::fetch();
    }

    /*
     * 发布医院公告
     * */
    public function add_yygg(){
        return View::fetch();
    }

    /*
     * 查看医院公告
     * */
    public function look_yygg(){
        return View::fetch();
    }

    /*
     * 上传文件
     * 上传头像
     * */
    public function upload_files(){
        $data = upload_file('file','files');
        return json($data);
    }

    /*
     * 提交医院公告
     * */
    public function add_yygg_list(){
        $data = Request::post();
        $data['publisherID'] = session('user_id');
        $user_info = get_user_info();
        $data['datetime'] = date('Y-m-d H:i:s',time());
        $data['type'] = 1;
        $data['publisher'] = $user_info['name'];
        $save = Db::name('tongzhi_list')->save($data);
        if($save){
            return json(rMsg(1,'添加成功'));
        }else{
            return json(rMsg(2,'添加失败'));
        }
    }

    /*
     * 获取医院公告接口
     *
     * */
    public function yygg_list(){
        $limit = Request::param('limit');
        $list['data'] = Db::name('tongzhi_list')->where('type',1)->order('id', 'desc')->paginate($limit);
        if($list){
            $list['code'] = 0;
            $list['msg'] = '请求成功';
        }else{
            $list['code'] = 1;
            $list['msg'] = '请求失败';
        }
        return json($list);
    }

    /*
     * 医院公告信息
     *
     * */
    public function yygg_info($id){
        $info = Db::name('tongzhi_list')->find($id);
        View::assign('title',$info['title']);
        View::assign('text',$info['text']);
        View::assign('id',$id);
        return View::fetch();
    }

    /*
     * 获取文件列表
     * */
    public function file_list($id){
        $list = Db::name('tongzhi_list')->find($id);
        if($list){
            $file_lis_data = explode(',',$list['files']);
            foreach ($file_lis_data as $key => $value){
                $data = explode('/',$value);
                $file_list[$key]['name'] = $data[4];
                $file_list[$key]['path'] = $value;
            }
            $data_list['code'] = 0;
            $data_list['msg'] = '请求成功';
            $data_list['data'] = $file_list;
        }else{
            $data_list['code'] = 1;
            $data_list['msg'] = '请求失败';
        }
        return json($data_list);
    }


    /*
     * 测试专用
     *
     * */
    public function test(){
        $list = Db::name('tongzhi_list')->where('id',13)->find();
        $file_lis_data = explode(',',$list['files']);
        foreach ($file_lis_data as $key => $value){
            $data = explode('/',$value);
            $file_list[$key]['name'] = $data[4];
            $file_list[$key]['path'] = $value;
        }
//        $data = $file_list;
        dump($file_list);
    }
}