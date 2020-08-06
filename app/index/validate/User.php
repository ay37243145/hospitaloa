<?php


namespace app\index\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'=>'require',
        'password'=>'require|min:6'
    ];
    protected $message = [
        'username.require'=>'用户名不能为空',
        'password.require'=>'密码不能为空',
        'password.min'=>'密码不能小于6位'
    ];
    protected $scene = [
        'dologin'=>['username','password']
    ];
}