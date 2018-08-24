<?php

namespace App\Console\Commands;

use App\LoginInfo;
use Icharle\Wxtool\Wxtool;
use Illuminate\Console\Command;
use swoole_websocket_server;
use Illuminate\Support\Facades\Redis;

class Swoole extends Command
{
    public $ws;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Active Push Message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arg = $this->argument('action');
        switch ($arg) {
            case 'start':
                $this->info('swoole server started');
                $this->start();
                break;
            case 'stop':
                $this->info('swoole server stoped');
                break;
            case 'restart':
                $this->info('swoole server restarted');
                break;
        }
    }

    /**
     * 启动Swoole
     */
    private function start()
    {
        $this->ws = new swoole_websocket_server("0.0.0.0", 9502);
        //监听WebSocket连接打开事件
        $this->ws->on('open', function ($ws, $request) {
            $this->info("client is open\n");
            // 生成小程序码
            $tool = new Wxtool();
            $scene = uniqid() . mt_rand(100000, 999999);             // 场景值(随机生成)
            $img = $tool->GetQrcode($scene, 'pages/other/main');
            LoginInfo::create(['scene' => $scene]);
            Redis::set($scene,$request->fd);            // 保存场景值对应会话ID
            $this->ws->push($request->fd, json_encode(['image' => $img]));    // 返回给client端
        });
        //监听WebSocket消息事件
        $this->ws->on('message', function ($ws, $frame) {
            $this->info("client is SendMessage\n");
        });
        //监听WebSocket主动推送消息事件
        $this->ws->on('request', function ($request, $response) {
            $this->info($request->post['scene']);
            $this->info(Redis::get($request->post['scene']));
            $this->ws->push(Redis::get($request->post['scene']),json_encode(['userinfo' => 'Icharle','msg' => '登录成功']));
            $this->info("client is PushMessage\n");
        });
        //监听WebSocket连接关闭事件
        $this->ws->on('close', function ($ws, $fd) {
            $this->info("client is close\n");
        });
        $this->ws->start();
    }
}
