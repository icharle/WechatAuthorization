<?php

namespace App\Http\Controllers;

use App\LoginInfo;
use App\User;
use Carbon\Carbon;
use Icharle\Wxtool\Wxtool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WechatController extends Controller
{
    public function __construct()
    {
        $this->middleware('token.refresh', ['except' => ['GetQrcode', 'GetToken']]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 微信授权登录
     */
    public function GetToken(Request $request)
    {
        $wechat = new Wxtool();
        $code = $request->code;
        $encryptedData = $request->encryptedData;
        $iv = $request->iv;
        $wechat->GetSessionKey($code);           //获取用户openid 和 session_key
        $userinfo = json_decode($wechat->GetUserInfo($encryptedData, $iv));   //获取用户详细信息
        // 不存在则创建 存在则更新
        $user = User::updateOrCreate(
            ['openId' => $userinfo->openId], [
                'nickName' => $userinfo->nickName,
                'avatarUrl' => $userinfo->avatarUrl,
                'gender' => $userinfo->gender,
                'city' => $userinfo->city,
                'province' => $userinfo->province,
                'country' => $userinfo->country,
                'language' => $userinfo->language
            ]
        );
        $token = Auth::guard('api')->fromUser($user);

        return response()->json([
            'token' => "Bearer " . $token
        ]);

    }
}
