<?php


namespace app\index\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'=>'require',
        'password'=>'require|min:6',
        'old_password'=>'require|min:6',
        'new_password'=>'require|min:6',
        'again_password'=>'require|min:6'
    ];
    protected $message = [
        'username.require'=>'用户名不能为空',
        'password.require'=>'密码不能为空',
        'password.min'=>'密码不能小于6位',
        'old_password.require'=>'旧密码不能为空',
        'old_password.min'=>'旧密码不能少于6位',
        'new_password.require'=>'新密码不能为空',
        'new_password.min'=>'新密码不能少于6位',
        'again_password.require'=>'重复新的密码不能为空',
        'again_password.min'=>'重复的新密码不能少于6位',
    ];
    protected $scene = [
        'dologin'=>['username','password'],
        'edit_user_info'=>['username'],
        're_pwd'=>['old_password','new_password','again_password']
    ];
}