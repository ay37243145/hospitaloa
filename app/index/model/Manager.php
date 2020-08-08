<?php


namespace app\index\model;

use app\index\validata\ManagerValidate;
use app\index\validate\User;
use think\exception\ValidateException;
use think\facade\Db;
use think\Model;

class Manager extends Model
{
    /*
     * 处理登录验证
     * */
    public static function checkLogin($data)
    {
        //数据的基础验证
        try {
            validate(User::class)->scene('dologin')->check($data);
//            return array('code'=>1,'msg'=>'登录成功');
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
//            return array('code'=>0,'msg'=>$e->getError());
            return rMsg(0,$e->getError());
        }
        $user_info = Db::name('users')->where('username',$data['username'])->find();
        if(!$user_info){
//            return  array('code'=>1,'msg'=>'用户名不存在');
            return rMsg(1,'用户名不存在');
        }elseif ($user_info['password']!=$data['password']){
//            return  array('code'=>2,'msg'=>'密码不正确');
            return rMsg(2,'密码不正确');
        }else{
            session('user_id',$user_info['id']);
//            return  array('code'=>3,'msg'=>'登录成功');
            return rMsg(3,'登录成功');
        }
    }

    public static function check_edit_user_info($data){
        try {
            validate(User::class)->scene('edit_user_info')->check($data);
        } catch (ValidateException $e){
            return rMsg(0,$e->getError());
        }
        $save = Db::name('users')->save([
            'username'=>$data['username'],
            'avatar'=>$data['avatar'],
            'sign'=>$data['sign']
        ]);
        if($save){
            return rMsg(2,'保存成功');
        }else{
            return rMsg(1,'保存失败，请重试！');
        }
    }
}