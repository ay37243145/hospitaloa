<?php
declare (strict_types=1);

namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
//        return url('home1');
        return View::fetch();
    }

    public function home1()
    {
        return View::fetch();
    }

    public function home2()
    {
        return View::fetch();
    }

    public function home3()
    {
        return View::fetch();
    }


    //消息盒子
    public function msgbox(){
        return View::fetch();
    }

    //获取用户信息
    public function aa()
    {
        $id = 1;
        $mime = Db::name('users')->field('id,username,status,sign,avatar')->find($id);
        $friend_list = Db::name('users')->field('id,username,status,sign,avatar')->select();
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

    // 获取初始化数据
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

    // 获取菜单列表
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

    //递归获取子菜单
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

