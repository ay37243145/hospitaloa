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
     * 发布院级培训通知
     * */
    public function add_yjpxtz(){
        return View::fetch();
    }

    /*
     * 发布科室培训通知
     * */
    public function add_kspxtz(){
        return View::fetch();
    }

    /*
     * 查看医院公告
     * */
    public function look_yygg(){
        return View::fetch();
    }

    /*
     * 查看院级培训通知
     * */
    public function look_yjpxtz(){
        return View::fetch();
    }

    /*
     * 查看科室培训通知
     * */
    public function look_kspxtz(){
        return View::fetch();
    }

    /*
     * 上传文件
     * 上传附件
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
        $data['signer'] = $user_info['id'];
        if($save){
            return json(rMsg(1,'添加成功'));
        }else{
            return json(rMsg(2,'添加失败'));
        }
    }

    /*
     * 提交院级培训
     * */
    public function add_yjpx_list(){
        $data = Request::post();
        $data['fbrID'] = session('user_id');
        $user_info = get_user_info();
        $data['fbsj'] = date('Y-m-d H:i:s',time());
        $data['lx'] = 1;
        $data['fbr'] = $user_info['name'];
        $data['qsr'] = $user_info['id'];
        $save = Db::name('pxtz_list')->save($data);
        if($save){
            return json(rMsg(1,'添加成功'));
        }else{
            return json(rMsg(2,'添加失败'));
        }
    }

    /*
     * 提交科室培训
     * */
    public function add_kspx_list(){
        $data = Request::post();
        $data['fbrID'] = session('user_id');
        $user_info = get_user_info();
        $data['fbsj'] = date('Y-m-d H:i:s',time());
        $data['lx'] = 2;
        $data['fbr'] = $user_info['name'];
        $data['qsr'] = $user_info['id'];
        $save = Db::name('pxtz_list')->save($data);
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
     * 获取院级培训通知接口
     * */
    public function yjpxtz_list(){
        $limit = Request::param('limit');
        $list['data'] = Db::name('pxtz_list')->where('lx',1)->order('id', 'desc')->paginate($limit);
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
     * 获取科室培训通知接口
     * */
    public function kspxtz_list(){
        $limit = Request::param('limit');
        $list['data'] = Db::name('pxtz_list')->where('lx',2)->order('id', 'desc')->paginate($limit);
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
     * 培训通知信息
     * */
    public function pxtz_info($id){
        $info = Db::name('pxtz_list')->find($id);
        View::assign('title',$info['title']);
        View::assign('text',$info['text']);
        View::assign('id',$id);
        View::assign('zjr',$info['zjr']);
        View::assign('cyr',$info['cyr']);
        View::assign('zysx',$info['zysx']);
        return View::fetch();
    }

    /*
     * 获取医院公告文件列表
     * */
    public function file_list($id){
        $list = Db::name('tongzhi_list')->find($id);
        if($list['files']!=''){
            $file_lis_data = explode(',',$list['files']);
            foreach ($file_lis_data as $key => $value){
                $data = explode('/',$value);
                $file_list[$key]['name'] = $data[4];
                $file_list[$key]['path'] = $value;
            }
            $data_list['code'] = 0;
            $data_list['msg'] = '请求成功';
            $data_list['data'] = $file_list;
        }else if($list['files']==''){
            $data_list['code'] = 0;
            $data_list['msg'] = '请求成功';
            $data_list['data'] = [];
        }else{
            $data_list['code'] = 1;
            $data_list['msg'] = '请求失败';
        }
        return json($data_list);
    }

    /*
     * 获取培训通知文件列表
     * */
    public function pxtz_file_list($id){
        $list = Db::name('pxtz_list')->find($id);
        if($list['files']!=''){
            $file_lis_data = explode(',',$list['files']);
            foreach ($file_lis_data as $key => $value){
                $data = explode('/',$value);
                $file_list[$key]['name'] = $data[4];
                $file_list[$key]['path'] = $value;
            }
            $data_list['code'] = 0;
            $data_list['msg'] = '请求成功';
            $data_list['data'] = $file_list;
        }else if($list['files']==''){
            $data_list['code'] = 0;
            $data_list['msg'] = '请求成功';
            $data_list['data'] = [];
        }else{
            $data_list['code'] = 1;
            $data_list['msg'] = '请求失败';
        }
        return json($data_list);
    }

    /*
     * 医院公告点击加一
     * */
    public function yygg_dj($id){
        $updata = Db::name('tongzhi_list')->where('id',$id)->inc('read_num')->update();
        if($updata){
            return json(1);
        }else{
            return json(2);
        }
    }

    /*
     * 培训通知点击加一
     * */
    public function pxtz_dj($id){
        $updata = Db::name('pxtz_list')->where('id',$id)->inc('ydsl')->update();
        if($updata){
            return json(1);
        }else{
            return json(2);
        }
    }

    /*
     * 签收医院公告
     * */
    public function qsyygg($id){
        $user_info = get_user_info();
        $list = Db::name('tongzhi_list')->find($id);
        $qsr_list = explode(',',$list['signer']);
        foreach ($qsr_list as $key => $value){
            $qsr_list[$key] = intval($value);
        }
        $qsr_list[] = $user_info['id'];
        $qsr_data = implode(',',$qsr_list);
        $updata = Db::name('tongzhi_list')->where('id',$id)->update(['signer'=>$qsr_data]);
        if($updata){
            return  json(rMsg(1,'签收成功!'));
        }else{
            return  json(rMsg(2,'签收失败，请重试！'));
        }
    }

    /*
     * 签收培训通知
     * */
    public function qspxtz($id){
        $user_info = get_user_info();
        $list = Db::name('pxtz_list')->find($id);
        $qsr_list = explode(',',$list['qsr']);
        foreach ($qsr_list as $key => $value){
            $qsr_list[$key] = intval($value);
        }
        $qsr_list[] = $user_info['id'];
        $qsr_data = implode(',',$qsr_list);
        $updata = Db::name('pxtz_list')->where('id',$id)->update(['qsr'=>$qsr_data]);
        if($updata){
            return  json(rMsg(1,'签收成功!'));
        }else{
            return  json(rMsg(2,'签收失败，请重试！'));
        }
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