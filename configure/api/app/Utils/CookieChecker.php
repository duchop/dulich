<?php
namespace App\Utils;

use App\Constants\CommonConst;

class CookieChecker
{

    /**
     * APIユーザーIDクッキーにセットする
     *
     * @param int $api_user_id
     *            APIユーザーID
     */
    public static function setUserApiCookie($api_user_id)
    {
        $hash = md5(sprintf("%s%s%s", $api_user_id, CommonConst::COOKIE_KEY5, request()->header('User-Agent')));
        $value = $api_user_id . ':' . $hash;
        setcookie(CommonConst::API_USER_COOKIE, $value, 0, '/api/', '', true, true);
    }

    /**
     * セッションを開始する
     */
    public static function sessionStart()
    {
        session()->setName(CommonConst::WEB_API_COOKIE);
        session()->start();

        if (! session()->has('expires')) {
            session()->put('expires', time());
        }

        // 1秒以内に同時アクセスされた場合も消えるので確率を設定
        if (mt_rand(1, 10) === 1) {
            // 最新のアクセスから1秒過ぎたらセッションIDを書き換え
            $expires = session()->get('expires');
            if ($expires + 1 < time()) {
                // 書き換え時間を再設定
                session()->put('expires', time());
                // セッションID切り替え
                session()->migrate(true);
            }
        }
    }

    /**
     * セッションが有効なままLoginページにアクセスされた際に切断する
     */
    public static function sessionDestroy()
    {
        if (session()->getName() !== null) {
            setcookie(session()->getName(), '', time() - 42000, '/');
        }

        session()->flush();
    }
}
