<?php

namespace App\Http\Middleware;

use App\Models\CustomerModel;
use Closure;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
        if (!$token) {
            return responseHttpStatus(400, 'token缺失');
        }
        $userInfo = CustomerModel::getUserInfo(array('token' => $token));
        if ($userInfo) {
            $info = array(
                '_nick_name' => $userInfo['nick_name'],
                '_mobile' => $userInfo['mobile']
            );
            $request->attributes->add($info);
        } else {
            return responseHttpStatus(401, '登录失效，请重新登录');
        }
        return $next($request);
    }
}
