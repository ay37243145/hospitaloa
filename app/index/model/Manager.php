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

    /*
     * 处理编辑用户信息验证
     * */
    public static function check_edit_user_info($data){
        try {
            validate(User::class)->scene('edit_user_info')->check($data);
        } catch (ValidateException $e){
            return rMsg(0,$e->getError());
        }
        $updata = Db::name('users')->where('id',session('user_id'))->update([
            'username'=>$data['username'],
            'avatar'=>$data['avatar'],
            'sign'=>$data['sign']
        ]);
        if($updata){
            return rMsg(2,'保存成功');
        }else{
            return rMsg(1,'保存失败，请重试！');
        }
    }

    /*
     * 处理修改密码验证
     * */
    public static function check_re_pwd($data){
        try {
            validate(User::class)->scene('re_pwd')->check($data);
        } catch (ValidateException $e){
            return rMsg(0,$e->getError());
        }
        //当前用户信息
        $user_info = Db::name('users')->find(session('user_id'));
        if($data['old_password']!=$user_info['password']){
            return rMsg(1,'旧密码输入不正确');
        }elseif ($data['old_password']==$data['new_password']){
            return rMsg(2,'新密码不能和旧密码一样');
        }elseif ($data['new_password']!=$data['again_password']){
            return rMsg(3,'两次输入密码不一致');
        }else{
            $updata = Db::name('users')->where('id',session('user_id'))->update(['password'=>$data['new_password']]);
            if(!$updata){
                return rMsg(4,'密码重置失败，请重试');
            }else{
                return rMsg(5,'密码重置成功');
            }
        }
    }
}