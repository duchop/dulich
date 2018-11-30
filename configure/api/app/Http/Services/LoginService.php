<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Http\Models\ApiUserModel;
use App\Utils\UserBaseEX;
use App\Utils\Util;
use App\Exceptions\ApiException;
use App\Utils\CookieChecker;

class LoginService
{

    /**
     * ログイン情報の確認
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function login()
    {
        try {
            $ary_post_data = request()->all();

            /**
             *
             * @var $user_base_ex UserBaseEX
             */
            $user_base_ex = app(UserBaseEX::class);

            try {
                $result = $user_base_ex->getUserInfoByEmail($ary_post_data['mail']);
            } catch (\Exception $ex) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_09);
            }

            if ($result === false) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_09);
            } elseif (is_null($result)) {
                throw new ApiException(ErrorCodeConst::ERR_CODE_10);
            } else {
                $pass_user_db = Util::passDecryption($result[0]->gwsUser->loginPassword);
                if ($pass_user_db != $ary_post_data['pass']) {
                    throw new ApiException(ErrorCodeConst::ERR_CODE_10);
                }
                $api_user_id = $result[0]->gwsUser->apiUserId;
            }

            // ユーザーの使用状態を確認する。
            $this->checkLogin($api_user_id);

            CookieChecker::sessionStart();
            CookieChecker::setUserApiCookie($api_user_id);
            session()->put('api_user_id', $api_user_id);
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * ユーザーの使用状態を確認する。
     *
     * @param $api_user_id ユーザーのID
     */
    private function checkLogin($api_user_id)
    {
        try {
            $ary_column = [
                'API_USER_ID',
                'STATUS'
            ];
            $result = ApiUserModel::where('API_USER_ID', $api_user_id)->first($ary_column);

            if (! $result) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        // ユーザーの使用状態を確認する。
        if ($result['STATUS'] == 0) {
            throw new ApiException(ErrorCodeConst::ERR_CODE_33);
        } elseif ($result['STATUS'] == 2) {
            throw new ApiException(ErrorCodeConst::ERR_CODE_34);
        }
    }
}
