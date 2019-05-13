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

/**
 * 获取数据都定位信息
 * @param string $type
 * @param string $mobile
 * @return array|bool|mixed
 * @author Qiu
 */
function lbs_logistics($type = 'queryBalance', $mobile = '')
{
    $url = 'http://pin.shujudu.cn/api/pin';
    $parameters = 'key=0f684766f5&secret=eef5e000803e6e7969e0817fb12a04c2';
    switch ($type) {
        //查询接口余额
        case 'queryBalance':
            $url = $url . '/authlbsquery/' . '?' . $parameters;
            break;
        //手机注册接口(添加进白名单)
        case 'sendListAdd':
            $url = $url . '/authlbsopen/' . '?' . $parameters . '&mobile=' . $mobile;
            break;
        //查询白名单
        case 'sendListQuery':
            $url = $url . '/authlbsstatus/' . '?' . $parameters . '&mobile=' . $mobile;
            if (\App\Models\MongoDb\LocationWhiteListModel::getOne($mobile)) {
                return array('resid' => 1);
            } else {
                return array('resid' => 0);
            }
            break;
        //删除白名单
        case 'sendListDel':
            $url = $url . '/authlbsclose/' . '?' . $parameters . '&mobile=' . $mobile;
            break;
        //定位
        case 'sendLocation':
            $url = $url . '/authlbsquery/' . '?' . $parameters . '&mobile=' . $mobile;
            break;
    }

    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_POST,1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode == 200) {
        //接口成功返回数据
        $data = json_decode($resp, true);
        $result = $data;
    } else {
        //返回失败
        $result = false;
    }
    curl_close($ch);
    return $result;
}
