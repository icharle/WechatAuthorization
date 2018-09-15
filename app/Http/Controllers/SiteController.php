<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckSiteRequest;
use App\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{

    public function __construct()
    {
        $this->middleware('token.refresh');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 返回该用户的所有站点
     */
    public function index()
    {
        $userInfo = Auth::guard('api')->user();
        $res = $userInfo->site;
        return response()->json([
            'status' => 200,
            'data' => $res
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param CheckSiteRequest $request
     * @return \Illuminate\Http\JsonResponse
     * 保存站点信息
     */
    public function store(CheckSiteRequest $request)
    {
        $data = $request->all();
        $userInfo = Auth::guard('api')->user();
        SiteInfo::create(['site' => uniqid(),'sitename' => $data['sitename'], 'sitelogo' => $data['sitelogo'], 'sitedesc' => $data['sitedesc'], 'openId_id' => $userInfo['openId']]);
        return response()->json([
            'status' => 200,
            'message' => 'Save success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $siteinfo = SiteInfo::where('id', $id)->first();
        return response()->json([
            'status' => 200,
            'data' => $siteinfo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CheckSiteRequest $request, $id)
    {
        $data = $request->all();
        $userInfo = Auth::guard('api')->user();
        SiteInfo::where('id', $id)->update(['sitename' => $data['sitename'], 'sitelogo' => $data['sitelogo'], 'sitedesc' => $data['sitedesc'], 'openId_id' => $userInfo['openId']]);
        return response()->json([
            'status' => 200,
            'message' => 'Update success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SiteInfo::destroy($id);
        return response()->json([
            'status' => 200,
            'message' => 'Delete success'
        ]);
    }
}
