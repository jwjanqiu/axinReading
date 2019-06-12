<?php

namespace App\Http\Controllers\WxAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxDecryptController extends Controller
{
    /**
     * 小程序获取加密数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function wxDataDecrypt(Request $request)
    {
        //小程序APP ID
        $app_id = $request->input('app_id');
        //小程序session_key
        $session_key = $request->input('session_key');
        //小程序encrypted_data
        $encrypted_data = $request->input('encrypted_data');
        //小程序iv
        $iv = $request->input('iv');
        $pc = new wxBizDataCrypt($app_id, $session_key);
        $err_code = $pc->decryptData($encrypted_data, $iv, $data);
        $data = json_decode($data, true);
        if ($err_code == 0) {
            return responseApi(1, '请求成功', $data);
        } else {
            return responseApi(0, '请求失败', $err_code);
        }
    }
}
