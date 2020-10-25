<?php


namespace app\index\controller;


use app\BaseController;
use think\facade\View;

class Jihua extends BaseController
{

    /*
     * 1计划列表
     * */
    public function index(){
        return View::fetch();
    }

    /*
     * 添加计划
     * */
    public function add(){
        return View::fetch();
    }
}