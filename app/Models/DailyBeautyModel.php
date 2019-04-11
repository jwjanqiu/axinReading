<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/4/10
 * Time: 14:19
 */

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class DailyBeautyModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'daily_beauty';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 获取daily_beauty列表
     * @param $current_page
     * @return mixed
     * @author Qiu
     */
    public static function getAllDailyBeauty($current_page)
    {
        $limit = 10;
        $offset = $current_page > 1 ? ($current_page - 1) * $limit : 0;
        $data = self::offset($offset)->limit($limit)->orderBy('create_time', 'desc')->get();
        //无标题数据赋值日常更新
        foreach ($data as $key => $value) {
            $data[$key]['_id'] = (string)$value['_id'];
            if ($value['title'] == '') {
                $data[$key]['title'] = '日常更新';
            }
            $data[$key]['image'] = $value['url'][0];
            $data[$key]['create_time'] = date('Y-m-d', $value['create_time']);
            unset($value['url']);
        }
        $temp = [];
        //按日期分类
        foreach ($data as $key => $value) {
            $temp[$value['create_time']][] = $value;
        }
        //清空data数据
        $data = [];
        //按日期分类
        foreach ($temp as $key => $value) {
            $data[] = array(
                'key' => $key,
                'value' => $value
            );
        }
        return $data;
    }

    /**
     * daily_beauty详情
     * @param $id
     * @return mixed
     * @author Qiu
     */
    public static function getBeautyDetail($id)
    {
        $data = self::select('url', 'title')->where('_id', (string)$id)->first();
        return $data;
    }
}
