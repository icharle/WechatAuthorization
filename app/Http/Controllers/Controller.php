<?php

namespace App\Http\Controllers;

use App\LoginInfo;
use Icharle\Wxtool\Wxtool;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * CURL请求
     * @param $data
     */
    public function curl($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:9502");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_exec($curl);
        curl_close($curl);
    }

    /**
     * @return mixed
     * 获取带参数小程序码
     */
    public function GetQrcode($site)
    {
        $wechat = new Wxtool();
        $scene = uniqid() . mt_rand(100000, 999999);             // 场景值(随机生成)
        $img = $wechat->GetQrcode($scene, 'pages/scan/main');
        LoginInfo::create(['scene' => $scene, 'site_id' => $site]);
        $arr = array('scene' => $scene, 'image' => $img);
        return $arr;
    }
}
