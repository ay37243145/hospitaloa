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

/**
 * 上传文件
 * @author 朝游东海
 * @param string $filename input框的name
 * @param string $filepath  存储路径
 * @param string $rule 验证规则
 * @param int $maxsize 允许文件上传的大小 默认为2m
 * @param bool $fileas 是否用原文件名上传保存 默认false
 * @return array
 */
function upload_file($filename='file',$filepath='images',$rule='fileExt:jpg,jpeg,png,gif,pem,doc,docx|fileMime:image/jpeg,image/gif,image/png,text/plain,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',$maxsize=2465791000,$fileas=false)
{
    $file = request()->file($filename);
    $fileas = true;
    try {
        if($file->getSize() > $maxsize){
            return rMsg(0,'上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件');

        }
        validate([$filename => $rule])->check([$filename => $file]);
        //$savename = Filesystem::disk('public')->putFile( $filepath, $file);  // /public/storage/images/5d16da691b5eb.png  根据内部方法生成文件名
        //$savename = Filesystem::disk('public')->putFileAs( $filepath.'/'.date('Ymd'), $file,$file->getOriginalName()); // /public/storage/images/Snipaste_2019-11-20_11-57-35.jpg  使用原文件名
        $savename = $fileas == false ? \think\facade\Filesystem::disk('public')->putFile( $filepath, $file) : \think\facade\Filesystem::disk('public')->putFileAs( $filepath.'/'.date('Ymd'), $file,$file->getOriginalName());
        return rMsg(1,'上传成功',['path'=>\think\facade\Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $savename)]);
    } catch (\think\exception\ValidateException $e) {
        return rMsg(0,$e->getMessage());
    }
}

/*
 * 组装返回的状态码
 * */
function rMsg($code = 0,$msg = '返回信息',$data = []){
    return ['code'=>$code,'msg'=>$msg,'data'=>$data];
}

/*
 * 多文件上传接口
 * */
function upload_files($filename='file',$filepath='images',$rule='fileExt:jpg,jpeg,png,gif,pem|fileMime:image/jpeg,image/gif,image/png,text/plain',$maxsize=2097152,$fileas=false){
    $files = request()->file($filename);
    try {
        foreach ($files as $file){
            if($file->getSize() > $maxsize){
                return rMsg(0,'上传失败,文件超出大小,请选择'.floor($maxsize/1024/1024) . 'm以内的文件');

            }
            validate([$filename => $rule])->check([$filename => $file]);
            //$savename = Filesystem::disk('public')->putFile( $filepath, $file);  // /public/storage/images/5d16da691b5eb.png  根据内部方法生成文件名
            //$savename = Filesystem::disk('public')->putFileAs( $filepath.'/'.date('Ymd'), $file,$file->getOriginalName()); // /public/storage/images/Snipaste_2019-11-20_11-57-35.jpg  使用原文件名
            $savename[] = $fileas == false ? \think\facade\Filesystem::disk('public')->putFile( $filepath, $file) : \think\facade\Filesystem::disk('public')->putFileAs( $filepath.'/'.date('Ymd'), $file,$file->getOriginalName());
        }
        return rMsg(1,'上传成功',['path'=>\think\facade\Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $savename)]);
        return rMsg(1,'上传成功',['path'=>\think\facade\Filesystem::getDiskConfig('public', 'url') . '/' . str_replace('\\', '/', $savename)]);
    } catch (\think\exception\ValidateException $e) {
        return rMsg(0,$e->getMessage());
    }
}
