<?php


namespace app\index\controller;


use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'http://0.0.0.0:2345';

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection,$data)
    {
//
        $list = json_decode($data,true);
        if(!$list){
            return;
        }
        switch ($list['type']){
            case 'con':
                $connection->send(json_encode($list));
                return;
            case 'send':
//                $mine = $list['data']['mime'];
//                $to = $list['data']['to'];
                $msg['type'] = 'receive';
                $msg['data'] = [
                    'username'=>$list['data']['to']['username'],       //消息来源用户名
                    'avatar'=>$list['data']['to']['avatar'],        //消息来源用户头像
                    'id'=>$list['data']['to']['id'],            //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                    'type'=>'friend',          //聊天窗口来源类型，从发送消息传递的to里面获取
                    'content'=>$list['data']['mine']['content'],       //消息内容
                    'cid'=>1,           //消息id，可不传。除非你要对消息进行一些操作（如撤回）
                    'mine'=>false,          //是否我发送的消息，如果为true，则会显示在右方
                    'fromid'=>$list['data']['to']['id'],        //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                    'timestamp'=>time()            //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                ];
                $connection->send(json_encode($msg));
                return;
        }
    }


}