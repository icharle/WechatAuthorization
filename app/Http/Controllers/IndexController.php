<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckAuthRequest;
use App\LoginInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('token.refresh', ['except' => ['CheckAuth']]);
    }

    /**
     * @param CheckAuthRequest $request
     * 轮询验证是否已经授权
     */
    public function CheckAuth(CheckAuthRequest $request)
    {
        $scene = $request->scene;       // 获取做参数
        $info = LoginInfo::where('scene', $scene)->first();     // 判断是否使用
        if (Cache::get($scene)) {        // 判断是否过期(五分钟时间)
            if (isset($info) && $info['status'] == 1) {
                $info->user();      // 已经授权登录则返回用户信息
            } else if (isset($info) && $info['status'] == 0) {
                // 未使用状态
            } else if (isset($info) && $info['status'] == 2) {
                // 已经使用(拒接状态) 重新刷新
            } else {
                // 随意参数问题
            }
        } else {
            // 重新刷新
        }
    }

    /**
     * @param Request $request
     * 小程序端给予权限
     */
    public function WxPutAuth(CheckAuthRequest $request)
    {
        $scene = $request->scene;       // 获取做参数
        return $scene;
    }
}
