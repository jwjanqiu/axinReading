<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/4/4
 * Time: 15:30
 */

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * 用户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function login(Request $request)
    {
        //手机号
        if ($request->has('mobile')) {
            $mobile = $request->input('mobile');
            //正则判断手机号格式是否正确
            $check = checkMobile($mobile);
            if (!$check) {
                return responseHttpStatus(400, '请输入正确手机号');
            }
        } else {
            return responseHttpStatus(400, '缺少手机号');
        }
        //密码
        if ($request->has('password')) {
            $password = $request->input('password');
            if (!trim($password)) {
                return responseHttpStatus(400, '请输入密码，不能含有空格');
            }
        } else {
            return responseHttpStatus(400, '缺少密码');
        }
        $data = CustomerModel::Login($mobile, $password);
        if ($data['code'] == 1) {
            unset($data['code']);
            return responseApi(1, '登录成功', $data);
        } else {
            return responseApi(0, $data['msg']);
        }
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function logout(Request $request)
    {
        $user_id = $request->get('_user_id');
        CustomerModel::logout($user_id);
        return responseApi(1, '已退出登录');
    }
}
