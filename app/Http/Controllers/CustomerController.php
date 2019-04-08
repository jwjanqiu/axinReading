<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * 修改用户数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function modifyUserInfo(Request $request)
    {
        $userInfo = array();
        //获取user_id
        $user_id = $request->get('_user_id');
        //获取用户名
        $user_name = $request->get('_nick_name');
        //获取旧密码
        $password = $request->get('_password');
        //获取新的用户名
        if ($request->has('user_name')) {
            $new_user_name = $request->input('user_name');
        } else {
            return responseHttpStatus(400, '缺少user_name参数');
        }
        //获取新的密码
        if ($request->has('password')) {
            $new_password = $request->input('password');
        } else {
            return responseHttpStatus(400, '缺少password参数');
        }
        //如果用户名和旧用户名不一致并且不为空则修改
        if ($new_user_name != $user_name && $new_user_name != '') {
            $userInfo['nick_name'] = $new_user_name;
        }
        //新密码和旧密码一致时不修改
        if ($new_password != ''){
            if (md5($new_password) != $password) {
                $userInfo['password'] = md5($new_password);
            } else {
                return responseApi(0, '新密码不能和旧密码一致');
            }
        }
        //修改用户数据
        $data = CustomerModel::modifyUserInfo($userInfo, $user_id);
        if ($data) {
            return responseApi(1, '修改成功', $data);
        } else {
            return responseApi(0, '修改失败，请稍后再试');
        }

    }
}
