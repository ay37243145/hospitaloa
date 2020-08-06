<?php


namespace app\index\model;

use app\index\validata\ManagerValidate;
use app\index\validate\User;
use think\exception\ValidateException;
use think\facade\Db;
use think\Model;

class Manager extends Model
{
    //处理登录验证
    public static function checkLogin($data)
    {
        //数据的基础验证
        try {
            validate(User::class)->scene('dologin')->check($data);
//            return array('code'=>1,'msg'=>'登录成功');
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return array('code'=>0,'msg'=>$e->getError());
        }
        $user_info = Db::name('user')->where('username',$data['username'])->find();
        if(!$user_info){
            return  array('code'=>1,'msg'=>'用户名不存在');
        }elseif ($user_info['password']!=$data['password']){
            return  array('code'=>2,'msg'=>'密码不正确');
        }else{
            return  array('code'=>3,'msg'=>'登录成功');
        }
    }
}