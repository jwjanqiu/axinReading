<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/3/27
 * Time: 15:00
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model;

class BookModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'popular_king';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 获取书籍内容
     * @param $id
     * @param $collection
     * @return mixed
     * @author Qiu
     */
    public static function getReading($id, $collection)
    {
        if ($id) {
            $data = DB::collection($collection)->where('_id', $id)->first();
            if ($data['chapter'] != 1) {
                $last_chapter = $data['chapter'] - 1;
                $last_chapter = DB::collection($collection)->where('chapter', $last_chapter)->first();
                $data['last_chapter'] = (string)$last_chapter['_id'];
            } else {
                $data['last_chapter'] = (string)$data['_id'];
            }
            $next_chapter = $data['chapter'] + 1;
            $next_chapter = DB::collection($collection)->where('chapter', $next_chapter)->first();
            $data['next_chapter'] = (string)$next_chapter['_id'];
        } else {
            $data = DB::collection($collection)->first();
            $data['last_chapter'] = '';
            $next_chapter = $data['chapter'] + 1;
            $next_chapter = DB::collection($collection)->where('chapter', $next_chapter)->first();
            $data['next_chapter'] = (string)$next_chapter['_id'];
        }
        $data['collection'] = $collection;
        unset($data['_id']);
        return $data;
    }

    /**
     * 书籍章节目录
     * @param $collection
     * @param $page
     * @return mixed
     * @author Qiu
     */
    public static function getCategory($collection, $page)
    {
        $limit = 10;
        $offset = $page > 1 ? ($page - 1) * $limit : 0;
        $data = DB::collection($collection)->select('_id', 'title')->offset($offset)->limit($limit)->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['_id'] = (string)$value['_id'];
            $data[$key]['title'] = $value['title'][0];
        }
        return $data;
    }
}
