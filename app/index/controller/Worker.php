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
//        $data['type'] = 'receive';
        $connection->send(json_encode($data));
    }


}