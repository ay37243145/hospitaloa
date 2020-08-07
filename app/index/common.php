<?php
// 这是系统自动生成的公共文件

/*
 * 获取当前登陆用户信息
 * id 用户ID
 * */
function get_user_info(){
    $id = session('user_id');
    $list = \think\facade\Db::name('users')->find($id);
    return $list;
}