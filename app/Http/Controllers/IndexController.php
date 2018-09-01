<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckAuthRequest;
use App\Http\Requests\CheckSiteRequest;
use App\LoginInfo;
use App\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('token.refresh', ['except' => ['UploadFile']]);
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

    /**
     * @param Request $request
     * @return mixed
     * 上传logo接口
     */
    public function UploadFile(Request $request)
    {
        $file = $request->file('logo');
        $filename = uniqid() . '.png';
        Storage::put('/public/logo/' . $filename, file_get_contents($file));  // 保存logo
        return Storage::url('logo/' . $filename);
    }

    /**
     * @param CheckSiteRequest $request
     * @return \Illuminate\Http\JsonResponse
     * 保存站点信息
     */
    public function SaveSite(CheckSiteRequest $request)
    {
        $data = $request->all();
        $userInfo = Auth::guard('api')->user();
        SiteInfo::create(['sitename' => $data['sitename'], 'sitelogo' => $data['sitelogo'], 'sitedesc' => $data['sitedesc'], 'openId_id' => $userInfo['openId']]);
        return response()->json([
            'status' => 200,
            'message' => 'Save success'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 返回该用户的所有站点
     */
    public function GetSite(Request $request)
    {
        $userInfo = Auth::guard('api')->user();
        $res = $userInfo->site;
        return response()->json([
            'status' => 200,
            'data' => $res
        ]);
    }
}
