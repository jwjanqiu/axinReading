<?php

namespace App\Http\Controllers;

use App\Models\DailyBeautyModel;
use Illuminate\Http\Request;

class LeadingController extends Controller
{
    /**
     * 获取daily_beauty列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function getDailyBeauty(Request $request)
    {
        //获取分页
        if ($request->has('page')) {
            $current_page = $request->input('page');
        } else {
            $current_page = 1;
        }
        $data = DailyBeautyModel::getAllDailyBeauty($current_page);
        return responseApi(1, '请求成功', $data);
    }

    /**
     * daily_beauty详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function getBeautyDetail(Request $request)
    {
        //获取id
        if ($request->has('id')) {
            $id = $request->input('id');
        } else {
            return responseHttpStatus(400, '缺少id参数');
        }
        $data = DailyBeautyModel::getBeautyDetail($id);
        return responseApi(1, '请求成功', $data);
    }
}
