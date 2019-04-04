<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/3/28
 * Time: 14:25
 */


/**
 * 公共返回方法
 * @param int $code
 * @param string $msg
 * @param array $data
 * @param array $headers
 * @return \Illuminate\Http\JsonResponse
 * @author Qiu
 */
function responseApi($code = 1, $msg = '请求成功', $data = array(), $headers = array())
{
    $data = array(
        'code' => $code,
        'msg' => $msg,
        'data' => (empty($data) || (!$data)) ? NULL : $data
    );
    return response()->json($data, 200, $headers);
}

/**
 * @param int $code
 * @param string $msg
 * @param array $data
 * @param array $headers
 * @return \Illuminate\Http\JsonResponse
 * @author Qiu
 */
function responseHttpStatus($code = 200, $msg = '请求成功', $data = array(), $headers = array())
{
    $data = array(
        'code' => $code,
        'msg' => $msg,
        'data' => (empty($data) || (!$data)) ? NULL : $data
    );
    if (isset($GLOBALS['version']) && $GLOBALS['version'] >= '1.3.0') {
        //私钥加密
        $rsaObj = new Rsa();
        $server_encrypt_private = File::get('../rsakey/server_encrypt_private.key');
        $data_resp = $rsaObj->rsaEncrypt(json_encode($data), $server_encrypt_private);
        return response()->json(['data' => $data_resp], $code, $headers);
    } else {
        return response()->json($data, $code, $headers);
    }
}

/**
 * 随机生成字符串
 * @return string
 * @author Qiu
 */
function randomKeys()
{
    $output = '';
    for ($a = 0; $a < 8; $a++) {
        $output .= chr(mt_rand(33, 126));
    }
    return $output;
}

/**
 * 判断手机号
 * @param $mobile
 * @return bool
 * @author Qiu
 */
function checkMobile($mobile)
{
    if (preg_match('/^1[1-9]\d{9}$/', $mobile)) {
        return true;
    } else {
        return false;
    }
}
