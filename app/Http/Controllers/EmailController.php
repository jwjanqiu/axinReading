<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class emailController extends Controller
{
    /**
     * 发送邮件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Qiu
     */
    public function sendMail(Request $request)
    {
        \Log::info('邮件信息：' . json_encode($request->input()));
        $name = $request->input('name');
        $text1 = $request->input('text1');
        $text2 = $request->input('text2');
        $text3 = $request->input('text3');
        if ($request->has('toAddress')) {
            $to_address = $request->input('toAddress');
        } else {
            return responseHttpStatus(400, '缺乏收件人信息');
        }
        $bg = 'http://zyb-buck01.oss-cn-shenzhen.aliyuncs.com/Public/upload/wx_image/5c41e70f6b552.png';
        $main = 'http://zyb-buck01.oss-cn-shenzhen.aliyuncs.com/Public/upload/wx_image/5c41e70f6ccc0.jpg';
        Mail::send('emails.email', ['name' => $name, 'text1' => $text1, 'text2' => $text2, 'text3' => $text3, 'bg' => $bg, 'main' => $main],
            function ($message) use ($to_address) {
                $to = $to_address;
                $message->to($to)->subject('你有一封新邮件，请注意查收');
            });
        if (count(Mail::failures()) < 1) {
            return responseApi(1, '发送邮件成功，请查收！');
        } else {
            return responseApi('发送邮件失败，请重试！');
        }
    }
}
