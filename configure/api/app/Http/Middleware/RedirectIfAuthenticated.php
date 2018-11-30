<?php
namespace App\Http\Middleware;

use Closure;
use App\Constants\RegularConst;
use App\Constants\CommonConst;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $this->isAuthenticated()) {
            return redirect()->secure(CommonConst::APP_LOGIN);
        }

        return $next($request);
    }

    /**
     * ログイン済みの場合にtrueを返します。
     */
    public function isAuthenticated()
    {
        if (! empty($_COOKIE[CommonConst::API_USER_COOKIE])) {
            $check = preg_match(RegularConst::CHECK_API_USER_ID, $_COOKIE[CommonConst::API_USER_COOKIE], $match);
            $hash = md5(sprintf("%s%s%s", $match[1], CommonConst::COOKIE_KEY5, $_SERVER['HTTP_USER_AGENT']));
            if (! strcmp($match[2], $hash) && $check == true && ! empty($match[2])) {
                if (strcmp($match[1], session()->get('api_user_id')) != 0) {
                    return false;
                    exit();
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }
}
