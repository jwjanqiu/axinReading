<?php

namespace App\Http\Controllers;

use App\Models\BaseRentInfoModel;
use App\Models\CalRentModel;
use Illuminate\Http\Request;

class CalRentController extends Controller
{
    public function insertNewRent(Request $request)
    {
        //电费
        if ($request->has('electric_charge')) {
            $electric_charge = $request->input('electric_charge');
        } else {
            return responseHttpStatus(400, '请填写用电度数');
        }
        //水费
        if ($request->has('water_charge')) {
            $water_charge = $request->input('water_charge');
        } else {
            return responseHttpStatus(400, '请填写用水度数');
        }
        //上月电费
        if ($request->has('first_electric')) {
            $first_electric = $request->input('first_electric') != '' ? $request->input('first_electric') : 0;
        } else {
            $first_electric = 0;
        }
        //上月水费
        if ($request->has('first_water')) {
            $first_water = $request->input('first_water') != '' ? $request->input('first_water') : 0;
        } else {
            $first_water = 0;
        }
        $data = array(
            'electric_charge' => $electric_charge,
            'water_charge' => $water_charge,
            'first_electric' => $first_electric,
            'first_water' => $first_water,
            'user_id' => $request->get('_user_id')
        );
        $result = CalRentModel::insertNewRent($data);
        switch ($result) {
            case -1:
                return responseApi(0, '请先添加基础房租信息');
                break;
            case 1:
                return responseApi(1, '添加成功');
                break;
            default:
                return responseApi(1, '添加成功', $result);
                break;
        }
    }

    /**
     * 获取房租明细
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function getAllRent(Request $request)
    {
        $user_id = $request->get('_user_id');
        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        $data = CalRentModel::getAllRent($user_id, $page);
        return responseApi(1, '请求成功', $data);
    }

    /**
     * 新增或更新房租基础信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function addRentBaseInfo(Request $request)
    {
        //基础电费
        if ($request->has('electric')) {
            $electric = $request->input('electric');
        } else {
            return responseHttpStatus(400, '缺少基础电费');
        }
        //基础水费
        if ($request->has('water')) {
            $water = $request->input('water');
        } else {
            return responseHttpStatus(400, '缺少基础水费');
        }
        //基础房租
        if ($request->has('base_rent')) {
            $base_rent = $request->input('base_rent');
        } else {
            return responseHttpStatus(400, '缺少基础房租');
        }
        //用户user_id
        $user_id = $request->get('_user_id');
        $data = array(
            'electric' => (double)$electric,
            'water' => (double)$water,
            'base_rent' => (double)$base_rent,
            'user_id' => (string)$user_id
        );
        BaseRentInfoModel::addRentBaseInfo($data);
        return responseApi(1, '添加成功');
    }

    /**
     * 获取房租基础信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function getRentBaseInfo(Request $request)
    {
        $user_id = $request->get('_user_id');
        $data = BaseRentInfoModel::getRentBaseInfo($user_id);
        return responseApi(1, '请求成功', $data);
    }
}
