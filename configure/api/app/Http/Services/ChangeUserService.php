<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Http\Models\ApiUserModel;
use App\Utils\UserBaseEX;
use App\Utils\Util;
use App\Http\Models\ApiCorporageUserInfoModel;
use App\Http\Models\ApiIndivisualUserInfoModel;
use App\Http\Models\ApiPrefModel;
use App\Exceptions\ApiException;
use App\Http\Models\ApiChangeLogModel;
use Illuminate\Support\Facades\DB;
use App\Constants\CommonConst;

class ChangeUserService extends Service
{

    private $pref_list;

    private $error;

    /**
     * ユーザー情報変更画面を表示する。
     *
     * @param Request $request
     */
    public function changeInput()
    {
        $api_user_id = session()->get('api_user_id');
        $ary_post_data = request()->all();

        try {
            if (empty($ary_post_data['user_id'])) {
                $ary_post_data = $this->getUserInfo($api_user_id);
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        // 都道府県を取得する。
        if ($ary_post_data['user_type'] == 1) {
            $pref_list = $this->getListPref();
            $this->setPref($pref_list);
            $ary_data['prefList'] = $pref_list;
        }

        $ary_data['user_data'] = $ary_post_data;

        return $ary_data;
    }

    /**
     * ユーザー情報変更を確認する。
     *
     * @param Request $request
     */
    public function changeConf()
    {
        try {
            $ary_post_data = request()->all();

            if (empty(request()->input('foreign_status_h'))) {
                $ary_post_data['foreign_status_h'] = 0;
            } elseif (empty(request()->input('foreign_status_k'))) {
                $ary_post_data['foreign_status_k'] = 0;
            }

            $ary_post_data['escapePass'] = Util::escapePass($ary_post_data['pass1']);

            // ユーザー基本EXにおけるユーザー情報を確認する。
            $this->checkExistUserBase($ary_post_data['user_id'], $ary_post_data['mail1']);

            if ($ary_post_data['user_type'] == 1) {
                $pref_h = ApiPrefModel::where('PREF_CODE', $ary_post_data['pref_h'])->first([
                    'PREF_NAME'
                ]);
                if (! $pref_h) {
                    throw new \Exception();
                }
                $pref_name = $pref_h->PREF_NAME;
            } else {
                $pref_name = 0;
            }

            $ary_data = [
                'postData' => $ary_post_data,
                'pref_h' => $pref_name
            ];

            return $ary_data;
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }
    }

    /**
     * ユーザーの登録が完了した。
     *
     * @param Request $request
     */
    public function changeComp()
    {
        try {
            $ary_post_data = request()->all();
            $api_user_id = session()->get('api_user_id');

            // ユーザー基本EXにおけるユーザー情報を確認する。
            $this->checkExistUserBase($ary_post_data['user_id'], $ary_post_data['mail1']);

            DB::beginTransaction();

            $ary_post_data['login_password'] = Util::passEncryption($ary_post_data['pass1']);
            $ary_post_data['user_name'] = $ary_post_data['user_name1'] . ' ' . $ary_post_data['user_name2'];
            $ary_post_data['e_mail'] = $ary_post_data['mail1'];

            if (empty($ary_post_data['accept_mail_magazine'])) {
                $ary_post_data['accept_mail_magazine'] = 0;
            }

            if ($ary_post_data['user_type'] == 0) {
                if ($ary_post_data['foreign_status_k'] == 1) {
                    $ary_post_data['foreign_status'] = 1;
                    $zip = null;
                } else {
                    $ary_post_data['foreign_status'] = 0;
                    $zip = $ary_post_data['zip_k_1'] . $ary_post_data['zip_k_2'];
                }
            } else {
                if ($ary_post_data['foreign_status_h'] == 1) {
                    $ary_post_data['foreign_status'] = 1;
                    $tel = null;
                    $zip = null;
                    $pref = null;
                    $city = null;
                    $street = null;
                } else {
                    $ary_post_data['foreign_status'] = 0;

                    $tel = $ary_post_data['tel_h_1'] . '-' .
                            $ary_post_data['tel_h_2'] . '-' .
                            $ary_post_data['tel_h_3'];

                    $zip = $ary_post_data['zip_h_1'] . $ary_post_data['zip_h_2'];
                    $pref = $ary_post_data['pref_h'];
                    $city = $ary_post_data['city_h'];
                    $street = $ary_post_data['street_h'];
                }
            }

            // API_USERテーブルを更新する。
            $user = ApiUserModel::where('API_USER_ID', $api_user_id)->where('STATUS', 1)->first();
            $user['UPDATE_USER'] = $api_user_id;
            $user['FOREIGN_STATUS'] = $ary_post_data['foreign_status'];
            $ret = $user->save();

            if (! $ret) {
                throw new \Exception();
            }
            $ary_changes_db = $user->getChanges();

            // 個人のユーザーの場合
            // API_INDIVISUAL_USER_INFOテーブルを更新する。
            if ($ary_post_data['user_type'] == 0) {
                $user_address = ApiIndivisualUserInfoModel::find($api_user_id);
                $user_address['ADDRESS_ZIP'] = $zip;
                $user_address['UPDATE_USER'] = $api_user_id;
                $ret = $user_address->save();

                // 会社のユーザーの場合
                // API_CORPORATE_USER_INFOテーブルを更新する。
            } elseif ($ary_post_data['user_type'] == 1) {
                $user_address = ApiCorporageUserInfoModel::find($api_user_id);
                $user_address['PHONE_NO'] = $tel;
                $user_address['ADDRESS_ZIP'] = $zip;
                $user_address['ADDRESS_PREF'] = $pref;
                $user_address['ADDRESS_CITY'] = $city;
                $user_address['ADDRESS_STREET'] = $street;
                $user_address['UPDATE_USER'] = $api_user_id;
                $ret = $user_address->save();
            }

            if (! $ret) {
                throw new \Exception();
            }

            $ary_changes_db = array_merge($ary_changes_db, $user_address->getChanges());

            // ユーザ更新情報取得
            $user_base = $this->getUserInfoByApiUserId($api_user_id);

            $ary_changes = $this->compareUserInfor($user_base, $ary_post_data);

            if ($ary_post_data['user_type'] == 1) {
                // 会社更新情報取得
                $user_address_userbase = $this->getCorporateUserInfoByApiUserId($api_user_id);

                $ary_changes = array_merge(
                    $ary_changes,
                    $this->compareUserAddress($user_address_userbase, $ary_post_data)
                );
            }
        } catch (ApiException $e) {
            dd($e);
            throw $e;
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();

            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        try {
            $ary_changes = array_merge($ary_changes_db, $ary_changes);
            $this->registLogDb($api_user_id, $ary_post_data['user_id'], $ary_post_data['user_type'], $ary_changes);

            /**
             *
             * @var $user_base_ex UserBaseEX
             */
            $user_base_ex = app(UserBaseEX::class);
            $ary_post_data['api_user_id'] = $api_user_id;

            if ($ary_post_data['user_type'] == 0) {
                $result = $user_base_ex->updatePersonalUserInfo($ary_post_data);
                if (! $result) {
                    throw new \Exception();
                }
            } elseif ($ary_post_data['user_type'] == 1) {
                $result = $user_base_ex->updateCorporateUserInfo($ary_post_data);
                if (! $result) {
                    throw new \Exception();
                }
            }

            DB::commit();
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();

            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * ユーザー基本EXのuser_id、mailを確認する。
     *
     * @param
     *            $user_id
     * @param
     *            $mail1
     * @return string|boolean 確認失敗、または、重複している場合はエラーメッセーを返す。
     */
    private function checkExistUserBase($user_id, $mail1)
    {
        /**
         *
         * @var $user_base UserBaseEX
         */
        $user_base = app(UserBaseEX::class);
        $result = $user_base->checkExistUserId($user_id);
        $api_user_id = session()->get('api_user_id');

        $ary_error = [];
        if ($result === false) {
            throw new \Exception();
        } elseif (! is_null($result) && $result[0]->gwsUser->apiUserId != $api_user_id) {
            $ary_error['user_id'] = ErrorCodeConst::ERR_CODE_13;
        }

        $result = $user_base->checkExistEmail($mail1);

        if ($result === false) {
            throw new \Exception();
        } elseif (! is_null($result) && $result[0]->gwsUser->apiUserId != $api_user_id) {
            $ary_error['mail1'] = ErrorCodeConst::ERR_CODE_13;
        }

        if (! empty($ary_error)) {
            $this->setDataError($ary_error);

            throw new ApiException(ErrorCodeConst::ERR_CHECK_DATA);
        }

        return true;
    }

    /**
     * ログを記録する。
     *
     * @param $api_user_id ユーザーのID
     * @param $user_id ユーザー名
     * @param
     *            $contents_type
     * @param
     *            $service_in
     */
    private function registLogDb($api_user_id, $user_id, $user_type, $ary_changes)
    {
        try {
            $result = ApiUserModel::where('API_USER_ID', $api_user_id)->first([
                'REGISTERED_DTIME'
            ]);

            array_pull($ary_changes, 'UPDATE_USER');
            array_pull($ary_changes, 'UPDATE_DTIME');

            foreach ($ary_changes as $key => $value) {
                $change_log = $key . CommonConst::LABEL_CHANGE;

                /**
                 *
                 * @var $change_log_model ApiChangeLogModel
                 */
                $change_log_model = app(ApiChangeLogModel::class);
                $change_log_model->API_USER_ID = $api_user_id;
                $change_log_model->CHANGE_LOG = $change_log;
                $change_log_model->LOG_USER = $user_id;
                $change_log_model->OPERATION_USER = $user_id;
                $change_log_model->REGISTRATION_DATE = $result['REGISTERED_DTIME'];
                $ret = $change_log_model->save();
            }
        } catch (\Exception $ex) {
            throw new \Exception();
        }

        if (! $ret) {
            throw new \Exception();
        }
    }

    /**
     * UserBasicEXの更新情報取得
     *
     * @param
     *            $ary_data
     */
    private function compareUserInfor($user_info, $user_info_to_update)
    {
        $ary_changes = [];
        if ($user_info->userId != $user_info_to_update['user_id']) {
            $ary_changes['USER_ID'] = $user_info_to_update['user_id'];
        }

        if ($user_info->userName != $user_info_to_update['user_name']) {
            $ary_changes['USER_NAME'] = $user_info_to_update['user_name'];
        }

        if ($user_info->eMail != $user_info_to_update['e_mail']) {
            $ary_changes['E_MAIL'] = $user_info_to_update['e_mail'];
        }

        if ($user_info->acceptMailMagazine != $user_info_to_update['accept_mail_magazine']) {
            $ary_changes['ACCEPT_MAIL_MAGAZINE'] = $user_info_to_update['accept_mail_magazine'];
        }

        if ($user_info->loginPassword != $user_info_to_update['login_password']) {
            $ary_changes['LOGIN_PASSWORD'] = $user_info_to_update['login_password'];
        }

        return $ary_changes;
    }

    /**
     * UserBasicEXの更新情報取得
     *
     * @param
     *            $ary_data
     */
    private function compareUserAddress($user_address, $user_address_to_update)
    {
        $ary_changes = [];
        if ($user_address->corporationName != $user_address_to_update['corporation_name']) {
            $ary_changes['CORPORATION_NAME'] = $user_address_to_update['corporation_name'];
        }

        if ($user_address->department != $user_address_to_update['department']) {
            $ary_changes['DEPARTMENT'] = $user_address_to_update['department'];
        }

        return $ary_changes;
    }

    /**
     * NganVV_COMMENT_CODE_0113
     *
     * @param List $pref
     */
    public function setPref($pref)
    {
        $this->pref_list = $pref;
    }

    /**
     * NganVV_COMMENT_CODE_0114
     *
     * @return List
     */
    public function getPref()
    {
        return $this->pref_list;
    }

    /**
     * NganVV_COMMENT_CODE_0115
     *
     * @param array $error
     */
    public function setDataError($error)
    {
        $this->error = $error;
    }

    /**
     * NganVV_COMMENT_CODE_0116
     *
     * @return array
     */
    public function getDataError()
    {
        return $this->error;
    }
}
