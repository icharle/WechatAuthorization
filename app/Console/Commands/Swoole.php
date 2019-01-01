<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use swoole_websocket_server;
use Illuminate\Support\Facades\Redis;

class Swoole extends Command
{
    public $ws;
    private $wechat;
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
        $this->wechat = new Controller();
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
//        $this->ws = new swoole_websocket_server("0.0.0.0", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
//        $this->ws->set(array(
//            'ssl_cert_file' => storage_path().'/ssl/fullchain.pem',
//            'ssl_key_file' => storage_path().'/ssl/fullchain.key',
//        ));
        $this->ws = new swoole_websocket_server("0.0.0.0", 9502);
        //监听WebSocket连接打开事件
        $this->ws->on('open', function ($ws, $request) {
            $site = $request->get['site'];          // 得到用户ID
            $this->info("client is open\n");
            // 生成小程序码
            $res = $this->wechat->GetQrcode($site);
            Redis::set($res['scene'], $request->fd);            // 保存场景值对应会话ID
            $this->ws->push($request->fd, json_encode(['image' => $res['image']]));    // 返回给client端
        });
        //监听WebSocket消息事件
        $this->ws->on('message', function ($ws, $frame) {
            $this->info("client is SendMessage\n");
        });
        //监听WebSocket主动推送消息事件
        $port = $this->ws->listen('127.0.0.1', 9501, SWOOLE_SOCK_TCP);
        $port->on('request', function ($request, $response) {
            $scene = $request->post['scene'];       // 获取场景值
            $userInfo = $request->post['userInfo'];
            $this->ws->push(Redis::get($scene), $userInfo);
            Redis::del($scene);
            $this->info("client is PushMessage\n");
        });
        //监听WebSocket连接关闭事件
        $this->ws->on('close', function ($ws, $fd) {
            $this->info("client is close\n");
        });
        $this->ws->start();
    }
}
