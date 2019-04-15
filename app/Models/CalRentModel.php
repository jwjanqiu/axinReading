<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class CalRentModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'house_rent';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 获取房租明细
     * @param $user_id
     * @param $current_page
     * @return mixed
     * @author Qiu
     */
    public static function getAllRent($user_id, $current_page)
    {
        $limit = 10;
        $offset = $current_page > 1 ? ($current_page - 1) * $limit : 0;
        $data = self::where('user_id', (string)$user_id)->offset($offset)->limit($limit)->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $data[$key]['date'] = date('Y/m/d', $value['date']);
                unset($data[$key]['_id']);
                unset($data[$key]['user_id']);
                unset($data[$key]['create_time']);
                unset($data[$key]['update_time']);
            }
        }
        return $data;
    }

    /**
     * 添加新房租信息
     * @param $data
     * @return int
     * @author Qiu
     */
    public static function insertNewRent($data)
    {
        //本月起始时间
        $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
        //本月结束时间
        $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        //上月起始时间
        $lastBMonth = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 1, 1, date('Y'))));
        //上月结束时间
        $lastEMonth = strtotime(date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), 0, date('Y'))));
        //本月搜索条件
        $condition[] = array('date', '>=', (int)$beginMonth);
        $condition[] = array('date', '<=', (int)$endMonth);
        $condition['user_id'] = (string)$data['user_id'];
        //上月搜索条件
        $last_condition[] = array('date', '>=', (int)$lastBMonth);
        $last_condition[] = array('date', '<=', (int)$lastEMonth);
        $last_condition['user_id'] = (string)$data['user_id'];
        //上月记录
        $last_info = self::where($last_condition)->first();
        //本月记录
        $info = self::where($condition)->first();
        //获取基本电费，水费，房租信息
        $base_info = BaseRentInfoModel::getRentBaseInfo($data['user_id']);
        //无基础数据时返回通知用户先添加基础数据
        if (!$base_info) {
            return -1;
        }

        //如果有上月记录
        if ($last_info) {
            //电费
            $electric_charge = ($data['electric_charge'] - $last_info['electric_charge']) * $base_info['electric'];
            //水费
            $water_charge = ($data['water_charge'] - $last_info['water_charge']) * $base_info['water'];
            //总费用
            $total_charge = $electric_charge + $water_charge + $base_info['base_rent'];
        } else {
            //用用户填写的上月信息
            //电费
            $electric_charge = ($data['electric_charge'] - $data['first_electric']) * $base_info['electric'];
            //水费
            $water_charge = ($data['water_charge'] - $data['first_water']) * $base_info['water'];
            //总费用
            $total_charge = $electric_charge + $water_charge + $base_info['base_rent'];
        }
        //如果有当月记录，则更新
        if ($info) {
            $info->update(array(
                'electric_charge' => (double)$data['electric_charge'],
                'water_charge' => (double)$data['water_charge'],
                'total_charge' => (double)$total_charge,
                'date' => (int)time(),
                'update_time' => (int)time()
            ));
        } else {
            //无则新增
            self::create(array(
                'electric_charge' => (double)$data['electric_charge'],
                'water_charge' => (double)$data['water_charge'],
                'total_charge' => (double)$total_charge,
                'date' => (int)time(),
                'user_id' => (string)$data['user_id'],
                'create_time' => (int)time(),
                'update_time' => (int)time()
            ));
        }
        return 1;
    }
}
