<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/4/4
 * Time: 15:56
 */

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Jenssegers\Mongodb\Eloquent\Model;

class CustomerModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'customer';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 登录
     * @param $mobile
     * @param $password
     * @return array
     * @author Qiu
     */
    public static function Login($mobile, $password)
    {
        //先查找是否存在该用户
        $result = self::where('mobile', (string)$mobile)->first();
        //存在则判断密码是否正确
        if ($result) {
            if (md5($password) == $result['password']) {
                //更新token
                $token = Crypt::encrypt(microtime());
                $result->update(array(
                    'token' => $token
                ));
                $data = array(
                    'code' => 1,
                    'token' => $token,
                    'nick_name' => $result['nick_name']
                );
            } else {
                $data = array(
                    'code' => 0,
                    'msg' => '密码不正确',
                );
            }
        } else {
            //不存在则新增用户
            $user_info = array(
                'nick_name' => randomKeys(),
                'mobile' => $mobile,
                'password' => md5($password),
                'token' => Crypt::encrypt(microtime())
            );
            if (self::create($user_info)) {
                $data = array(
                    'code' => 1,
                    'token' => $user_info['token'],
                    'nick_name' => $user_info['nick_name']
                );
            } else {
                $data = array(
                    'code' => 0,
                    'msg' => '未知原因无法注册，请稍后再试',
                );
            }
        }
        return $data;
    }

    /**
     * 获取用户相关信息
     * @param $condition
     * @param array $select
     * @return mixed
     * @author Qiu
     */
    public static function getUserInfo($condition, $select = array())
    {
        $data = self::select($select)->where($condition)->first();
        return $data;
    }
}
