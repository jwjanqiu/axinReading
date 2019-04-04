<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/3/27
 * Time: 15:11
 */

namespace App\Http\Controllers;

use App\Models\BookCateModel;
use App\Models\BookModel;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * 获取书籍内容
     * @param Request $request
     * @return JsonResponse
     * @author Qiu
     */
    public function getReading(Request $request)
    {
        $id = $request->input('id');
        $collection = $request->input('collection');
        $data = BookModel::getReading($id, $collection);
        return responseApi(1, '请求成功', $data);
    }

    /**
     * 获取所有书籍
     * @param Request $request
     * @return JsonResponse
     * @author Qiu
     */
    public function getAllBook(Request $request)
    {
        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        $data = BookCateModel::getAllBook($page);
        return responseApi(1, '请求成功', $data);
    }

    /**
     * 书籍章节目录
     * @param Request $request
     * @return JsonResponse
     * @author Qiu
     */
    public function getCategory(Request $request)
    {
        //获取书籍名
        if ($request->has('collection')) {
            $collection = $request->input('collection');
        } else {
            return responseHttpStatus(400, '缺少collection参数');
        }
        //分页
        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        $data = BookModel::getCategory($collection, $page);
        return responseApi(1, '请求成功', $data);
    }

}
