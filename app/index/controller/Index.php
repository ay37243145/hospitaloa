<?php
declare (strict_types=1);

namespace app\index\controller;

use app\BaseController;
use app\index\model\Manager;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Index extends BaseController
{
    /*
     * 加载框架
     * user_id 用户ID
     * $user_info 用户信息
     * */
    public function index()
    {
        if(!empty(session('user_id'))){
            $user_id = session('user_id');
            $user_info = Db::name('users')->where('id',$user_id)->find();
            View::assign('username',$user_info['username']);
            return View::fetch();
        }else{
            return redirect((string)url('index/login'));
        }
        /*$user_id = Session::get('user_id');
        if(empty($user_id)){
            return redirect((string)url('index/login'));
        }else{
            $user_name = Db::name('users')->field('username')->where('id',$user_id)->find();
        }*/
    }

    /*
     * 加载登录页
     * */
    public function login(){
        return View::fetch();
    }

    /*
     * 登陆验证
     * data 请求的数据
     * result 验证的结果
     * */
    public function dologin(){
        $data = Request::post();
        //返回模型中的处理结果，把post获取的$data传参给模型Model/manager.php中的checkLogin方法
        $result = Manager::checkLogin($data);
        return json($result);
    }

    /*
     * 加载主页
     * */
    public function home1()
    {
        return View::fetch();
    }

    /*
     * 加载公告
     * */
    public function home2()
    {
        return View::fetch();
    }

    /*
     * 加载待办
     * */
    public function home3()
    {
        return View::fetch();
    }


    /*
     * 加载消息盒子
     * 暂时废弃
     * */
    public function msgbox(){
        return View::fetch();
    }

    /*
     * 即时通讯
     * 获取用户信息
     * id 当前登陆用户ID
     * mime 发送消息的用户信息
     * friend_list 发送消息的好友列表
     * friend 前端接口的好友信息
     * list 返回前端的接口信息
     * */
    public function aa()
    {
        $id = session('user_id');
        $mime = Db::name('users')->field('id,username,status,sign,avatar')->find($id);
        $friend_list = Db::name('users')->field('id,username,status,sign,avatar')->where('id','<>',$id)->select();
        $friend = [array(
            'groupname'=>'哈哈',
            'id'=>1,
            'list'=>$friend_list
        )];
        $list = [
            'code'=>0,
            "msg"=>"",
            "data"=>['mine'=>$mime,"friend"=>$friend]
        ];
        return json($list);
    }

    /*
     * 初始化首页框架
     * homeinfo 首页数据
     * logoinfo 首页标题及logo
     * menuinfo 菜单列表
     * systeminit 拼接的框架内容
     * */
    public function getSystemInit()
    {
        $homeInfo = [
            'title' => '首页',
            'href' => 'index.php/index/index/home1',
        ];
        $logoInfo = [
            'title' => '办公系统',
            'image' => 'layui/images/logo.png',
        ];
        $menuInfo = $this->getMenuList();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        return json($systemInit);
    }

    /*
     * 获取框架主菜单列表
     * munulist 菜单列表
     *
     * */
    private function getMenuList()
    {
        $menuList = Db::name('system_menu')
            ->field('id,pid,title,icon,href,target')
            ->where('status', 1)
            ->order('sort', 'asc')
            ->select();
        $menuList = $this->buildMenuChild(0, $menuList);
        return $menuList;
    }

    /*
     * 递归获取子菜单
     * treelist 子菜单列表
     * menulist 菜单列表
     * pid 主菜单ID
     * node 主菜单
     * child 子菜单
     * */
    private function buildMenuChild($pid, $menuList)
    {
        $treeList = [];
        foreach ($menuList as $v) {
            if ($pid == $v['pid']) {
                $node = $v;
                $child = $this->buildMenuChild($v['id'], $menuList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                // todo 后续此处加上用户的权限判断
                $treeList[] = $node;
            }
        }
        return $treeList;
    }
}

