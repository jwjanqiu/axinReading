<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/3/27
 * Time: 17:28
 */

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BookCate extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'book_name';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 获取所有书籍
     * @param $current_page
     * @return BookCate[]|\Illuminate\Database\Eloquent\Collection
     * @author Qiu
     */
    public static function getAllBook($current_page)
    {
        $limit = 10;
        $offset = $current_page > 1 ? ($current_page - 1) * $limit : 0;
        $data = self::where('status', '1')->offset($offset)->limit($limit)->orderBy('create_time', 'desc')->get();
        return $data;
    }
}
