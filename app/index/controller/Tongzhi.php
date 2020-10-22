<?php


namespace app\index\controller;


use app\BaseController;
use think\facade\View;

class Tongzhi extends BaseController
{
    /*
     * 加载公告
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
     * */
    /*
     * 上传头像
     * */
    public function upload_files(){
        $data = upload_file('file','files');
        return json($data);
    }
}