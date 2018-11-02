<?php

namespace App\Http\Controllers\Web;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    public function getSocialRedirect($account)
    {
        try {
            return Socialite::with($account)->redirect();
        } catch (\InvalidArgumentException $e) {
            return redirect('/login');
        }
    }

    /**
     * User: Zoe
     * Date: 2018/11/2
     * Description：第三方登录的回调
     * @param $account - 第三方名称
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getSocialCallback($account)
    {
        // 从第三方 OAuth 回调中获取用户信息，socialUser 的格式参见 AuthenticationController 第 1 条
        $socialUser = Socialite::with($account)->user();
        // 在本地 users 表中查询用户来判断是否已存在
        $user = User::where('provider_id', '=', $socialUser->id)
            ->where('provider', '=', $account)
            ->first();
        if (null == $user) {
            // 如果用户不存在则将其保存到 users 表
            $newUser['name'] =$socialUser->getName();
            if (empty($newUser['name'])) {
                $newUser['name'] = $socialUser->getNickname();
            }
            if (empty($newUser['name'])) {
                $newUser['name'] = '';
            }
            $newUser['email'] = $socialUser->getEmail() == '' ? : $socialUser->getEmail();
            $newUser['avatar'] = $socialUser->getAvatar();
            $newUser['password'] = '';
            $newUser['provider'] = $account;
            $newUser['provider_id'] = $socialUser->getId();
            $user = User::create($newUser);
        }
        // 手动登录该用户
        Auth::login($user);
        // 登录成功后重定向到首页
        return redirect('/');
    }
}
