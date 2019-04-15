<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseRentInfoModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'base_rent';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 获取房租基础信息
     * @param $user_id
     * @return mixed
     * @author Qiu
     */
    public static function getRentBaseInfo($user_id)
    {
        $data = self::select(array('electric', 'water', 'base_rent'))->where('user_id', (string)$user_id)->first();
        unset($data['_id']);
        return $data;
    }

    /**
     * 新增或更新房租基础信息
     * @param $data
     * @author Qiu
     */
    public static function addRentBaseInfo($data)
    {
        //搜索是否存在记录
        $info = self::where('user_id', (string)$data['user_id'])->first();
        //存在就更新
        if ($info) {
            $data['update_time'] = (int)time();
            $info->update($data);
        } else {
            $data['create_time'] = (int)time();
            $data['update_time'] = (int)time();
            //不存在就新增
            self::create($data);
        }
    }
}
