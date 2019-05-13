<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * 获取定位信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function getLocation(Request $request)
    {
        //获取手机号码
        if ($request->has('mobile')) {
            $mobile = $request->input('mobile');
        } else {
            return responseHttpStatus(400, '缺少手机号');
        }
        //查询定位
        $result = lbs_logistics('sendLocation', $mobile);
        \Log::info('定位信息:' . json_encode($result));
        if ((int)$result['resid'] != 0) {
            return responseApi(0, $result['resmsg']);
        } else {
            return responseApi(1, '查询成功', $result);
        }
    }
}
