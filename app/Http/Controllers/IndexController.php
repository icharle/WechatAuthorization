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
        $this->middleware('auth:api', ['except' => ['CheckAuth']]);
    }

    public function CheckAuth(CheckAuthRequest $request)
    {
        $scene = $request->scene;       // 获取做兼职
        $info = LoginInfo::where('scene', $scene)->first();     // 判断是否使用
        if (Cache::get($scene)) {        // 判断是否过期(五分钟时间)
            if ($info['status'] == 1) {
                $info->user();      // 已经授权登录则返回用户信息
            } else if ($info['status'] == 0) {
                // 未使用状态
            } else if ($info['status'] == 2) {
                // 已经使用(拒接状态) 重新刷新
            }
        } else {
            // 重新刷新
        }
    }
}
