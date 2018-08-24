<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckAuthRequest;
use App\LoginInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('token.refresh');
    }

    /**
     * @param Request $request
     * 小程序端给予权限
     */
    public function WxPutAuth(CheckAuthRequest $request)
    {
        $scene = $request->scene;       // 获取做参数
        $userInfo = Auth::guard('api')->user();
        $res = LoginInfo::where('scene', $scene)->update(['status' => 1, 'openId_id' => $userInfo['openId']]);
        if ($res && Redis::get($scene)) {
            $param['userInfo'] = $userInfo;
            $param['scene'] = $scene;
            $this->curl($param);            // 主动推送消息
            return response()->json([
                'status' => 201,
                'message' => 'Authorized success'
            ]);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Authorized fail'
            ]);
        }
    }
}
