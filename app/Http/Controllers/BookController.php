<?php
/**
 * Created by PhpStorm.
 * User: PC-Qiu
 * Date: 2019/3/27
 * Time: 15:11
 */

namespace App\Http\Controllers;

use App\Models\BookCate;
use App\Models\BookModel;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * 获取书籍内容
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function getAllBook(Request $request)
    {
        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        $data = BookCate::getAllBook($page);
        return responseApi(1, '请求成功', $data);
    }

}
