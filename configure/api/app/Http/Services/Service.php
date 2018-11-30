<?php
namespace App\Http\Services;

use App\Http\Models\ApiCorporageUserInfoModel;
use App\Http\Models\ApiIndivisualUserInfoModel;
use App\Http\Models\ApiPrefModel;
use App\Http\Models\ApiUserModel;
use App\Utils\UserBaseEX;
use App\Constants\ErrorCodeConst;
use App\Utils\Util;

class Service
{
    public function __construct()
    {

    }

    /**
     * ユーザー基本EXをロールバックする。
     */
    protected function delInfoInUserBaseEX($user_type, $api_user_id)
    {
        /**
         *
         * @var $user_base UserBaseEX
         */
        $user_base = app(UserBaseEX::class);
        if ($user_type == 0) {
            $ret = $user_base->deletePersonalUserInfo($api_user_id);
        } elseif ($user_type == 1) {
            $ret = $user_base->deleteCorporateUserInfo($api_user_id);
        }

        return $ret;
    }

    /**
     * セッションに保存されているアクセスキーを確認する。
     *
     * @param $access_key フォームからアクセスキーを渡す。
     */
    protected function checkValidAccessKey($access_key)
    {
        if (count(session()->get('ACCESS_KEY_VALID')) >= 1) {
            return in_array($access_key, session()->get('ACCESS_KEY_VALID'));
        }
    }

    /**
     * ユーザー基本EXからユーザー情報を取得する。
     *
     * @param $api_user_id ユーザーのID
     * @throws Exception
     */
    protected function getUserInfoByApiUserId($api_user_id)
    {
        try {
            /**
             * @var $user_base UserBaseEX
             */
            $user_base = app(UserBaseEX::class);
            $result = $user_base->getUserInfoByApiUserId($api_user_id);
            if ($result === false || empty($result)) {
                throw new \Exception();
            }

            return $result[0]->gwsUser;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * No1のUSER_TYPEが1の場合、会社のユーザーのユーザー基本EXから会社の情報を取得する。
     *
     * @param $api_user_id ユーザーのID
     * @throws Exception
     */
    protected function getCorporateUserInfoByApiUserId($api_user_id)
    {
        try {

            /**
             * @var $user_base UserBaseEX
             */
            $user_base = app(UserBaseEX::class);
            $result = $user_base->getCorporateUserInfoByApiUserId($api_user_id);

            if ($result === false || empty($result)) {
                throw new \Exception();
            }

            return $result[0]->gwsCorporateUser[0];
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * 都道府県を取得する。
     *
     * @return boolean
     * @throws \Exception
     */
    public function getListPref()
    {
        try {
            $list_pref = ApiPrefModel::get([
                'PREF_CODE',
                'PREF_NAME'
            ]);

            return $list_pref;
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * ユーザー情報を取得する。
     *
     * @param $api_user_id
     * @return array
     * @throws \Exception
     */
    protected function getUserInfo($api_user_id)
    {
        try {
            $ary_colum = [
                'USER_TYPE',
                'FOREIGN_STATUS',
                'CONTENTS_NUM',
                'REGISTERED_DTIME'
            ];
            $user_info = ApiUserModel::where('API_USER_ID', $api_user_id)->where('STATUS', 1)->first($ary_colum);

            if (! $user_info) {
                throw new \Exception();
            } else {
                $user_type = $user_info['USER_TYPE'];
                $foreign_status = $user_info['FOREIGN_STATUS'];
            }

            // API_INDIVIDUAL_USER_INFOまたはAPI_CORPORATE_USER_INFOのDBオブジェクトを生成
            if ($foreign_status == 0) {
                if ($user_type == 0) {
                    $user_address = ApiIndivisualUserInfoModel::where('API_USER_ID', $api_user_id)->first([
                        'ADDRESS_ZIP'
                    ]);
                } elseif ($user_type = 1) {
                    $ary_colum = [
                        'PHONE_NO',
                        'ADDRESS_ZIP',
                        'ADDRESS_PREF',
                        'ADDRESS_CITY',
                        'ADDRESS_STREET'
                    ];
                    $user_address = ApiCorporageUserInfoModel::where('API_USER_ID', $api_user_id)->first($ary_colum);
                }

                if (! $user_address) {
                    throw new \Exception();
                }
            }

            // ユーザー基本EXからユーザー情報を取得する。
            $user_info_userbase = $this->getUserInfoByApiUserId($api_user_id);

            // No1のUSER_TYPEが1の場合、会社のユーザーのユーザー基本EXから会社の情報を取得する。
//            if ($user_type == 1) {
//                $adress_userbase = $this->getCorporateUserInfoByApiUserId($api_user_id);
//            }
        } catch (\Exception $ex) {
            throw new \Exception();
        }

        $post_data = [];
        $post_data['api_user_id'] = $user_info_userbase->apiUserId;
        $post_data['user_name'] = $user_info_userbase->userName;
        $post_data['user_id'] = $user_info_userbase->userId;
        $name = explode(" ", $user_info_userbase->userName);
        $post_data['user_name1'] = $name[0];
        $post_data['user_name2'] = $name[1];
        $post_data['mail1'] = $user_info_userbase->eMail;
        $post_data['mail2'] = $user_info_userbase->eMail;

        // パスワード復号化
        $login_password = Util::passDecryption($user_info_userbase->loginPassword);
        $post_data['pass1'] = $login_password;
        $post_data['pass2'] = $login_password;
        $post_data['user_type'] = $user_info['USER_TYPE'];
        $post_data['accept_mail_magazine'] = $user_info_userbase->acceptMailMagazine;
        $post_data['contents_num'] = $user_info['CONTENTS_NUM'];

        if ($user_type == 0) {
            $post_data['foreign_status_k'] = $user_info['FOREIGN_STATUS'];
            if ($post_data['foreign_status_k'] == 0) {
                $post_data['zip_k_1'] = substr($user_address['ADDRESS_ZIP'], 0, 3);
                $post_data['zip_k_2'] = substr($user_address['ADDRESS_ZIP'], 3, 4);
            }
        } elseif ($user_type == 1) {
            $post_data['foreign_status_h'] = $user_info['FOREIGN_STATUS'];
            $post_data['corporation_name'] = $adress_userbase->corporationName ?? '';
            $post_data['department'] = $adress_userbase->department ?? '';
            if ($post_data['foreign_status_h'] == 0) {
                $post_data['pref_h'] = $user_address['ADDRESS_PREF'];
                $post_data['city_h'] = $user_address['ADDRESS_CITY'];
                $post_data['street_h'] = $user_address['ADDRESS_STREET'];
                $tel = explode("-", $user_address['PHONE_NO']);
                $post_data['tel_h_1'] = $tel[0];
                $post_data['tel_h_2'] = $tel[1];
                $post_data['tel_h_3'] = $tel[2];
                $post_data['zip_h_1'] = substr($user_address['ADDRESS_ZIP'], 0, 3);
                $post_data['zip_h_2'] = substr($user_address['ADDRESS_ZIP'], 3, 4);
            }
        }

        return $post_data;
    }
}
