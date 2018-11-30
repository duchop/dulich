<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Http\Models\ApiProductsModel;
use App\Http\Models\ApiPrefModel;
use App\Constants\LogConst;
use App\Http\Models\ApiChangeLogModel;
use App\Constants\CommonConst;
use App\Http\Models\ApiUserModel;
use Illuminate\Support\Facades\DB;
use App\Constants\MailConst;
use App\Utils\SendMail;

class MypageService extends Service
{

    /**
     * 画面に表示するための情報を取得する。
     *
     * @param $api_user_id ユーザーのID
     * @throws Exception
     */
    public function getData($api_user_id)
    {
        try {
            // ポストデータ取得
            $user_data = $this->getUserData($api_user_id);
            $app_data = $this->getApplicationData($api_user_id);
            $ary_data = [
                'userData' => $user_data,
                'appData' => $app_data
            ];

            return $ary_data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * ユーザー情報を取得する。
     *
     * @param $api_user_id ユーザーのID
     * @throws Exception
     */
    private function getUserData($api_user_id)
    {
        try {
            $ary_user_data = $this->getUserInfo($api_user_id);

            if (array_has($ary_user_data, 'pref_h')) {
                $pref_code = $ary_user_data['pref_h'];
                $user_pref = ApiPrefModel::where('PREF_CODE', $pref_code)->first([
                    'PREF_NAME'
                ]);

                if (! $user_pref) {
                    throw new \Exception();
                }
                $ary_user_data['pref_h'] = $user_pref['PREF_NAME'];
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        return $ary_user_data;
    }

    /**
     * ユーザーのアプリケーション情報を取得する。
     *
     * @param $api_user_id ユーザーのID
     * @throws Exception
     */
    private function getApplicationData($api_user_id)
    {
        try {
            $ary_column = [
                'API_PRODUCTS_ID',
                'ACCESS_KEY',
                'CONTENTS_NAME',
                'CONTENTS_DESCRIPTION',
                'CONTENTS_TYPE',
                'UP_URL',
                'UP_URL_STATUS',
                'SERVICE_STATUS',
                'SERVICE_IN',
                'EXPIRE_STATUS',
                'EXPIRE_DATE',
                'EXPIRE_REASON_1',
                'EXPIRE_REASON_2',
                'EXPIRE_REASON_3',
                'EXPIRE_REASON_4',
                'EXPIRE_REASON_5'
            ];
            $application_info = ApiProductsModel::where('API_USER_ID', $api_user_id)
                                                    ->orderBy('REGIST_DTIME', 'asc')->get($ary_column);

            if (! $application_info) {
                throw new \Exception();
            } else {
                $contents_num = count($application_info);

                // アプリケーションのデータをビューに渡す。
                $ary_app_data = [];
                $ary_access_key = [];
                $ary_access_key_valid = [];

                for ($i = 0; $i < $contents_num; $i ++) {
                    $ary_app_data[$i]['contents_num'] = $contents_num;
                    $ary_app_data[$i]['access_key'] = $application_info[$i]['ACCESS_KEY'];
                    $ary_app_data[$i]['contents_name'] = $application_info[$i]['CONTENTS_NAME'];
                    $ary_app_data[$i]['contents_description'] = $application_info[$i]['CONTENTS_DESCRIPTION'];
                    $ary_app_data[$i]['contents_type'] = $application_info[$i]['CONTENTS_TYPE'];
                    $ary_app_data[$i]['up_url'] = $application_info[$i]['UP_URL'];
                    $ary_app_data[$i]['service_status'] = $application_info[$i]['SERVICE_STATUS'];
                    $ary_app_data[$i]['service_in'] = $application_info[$i]['SERVICE_IN'];
                    $ary_app_data[$i]['expire_status'] = $application_info[$i]['EXPIRE_STATUS'];
                    $ary_app_data[$i]['expire_date'] = isset($application_info[$i]['EXPIRE_DATE']) ?
                                                date("Y-m-d", strtotime($application_info[$i]['EXPIRE_DATE'])) : '';
                    $ary_app_data[$i]['expire_reason_1'] = $application_info[$i]['EXPIRE_REASON_1'];
                    $ary_app_data[$i]['expire_reason_2'] = $application_info[$i]['EXPIRE_REASON_2'];
                    $ary_app_data[$i]['expire_reason_3'] = $application_info[$i]['EXPIRE_REASON_3'];
                    $ary_app_data[$i]['expire_reason_4'] = $application_info[$i]['EXPIRE_REASON_4'];
                    $ary_app_data[$i]['expire_reason_5'] = $application_info[$i]['EXPIRE_REASON_5'];
                    $ary_app_data[$i]['up_url_status'] = $application_info[$i]['UP_URL_STATUS'];

                    // POSTデータチェック用全アクセスキーリスト
                    $ary_access_key[$i] = $application_info[$i]['ACCESS_KEY'];

                    // アプリ情報・有効期限更新可否判定用有効なアクセスキーリスト
                    if ($application_info[$i]['EXPIRE_STATUS'] == 0) {
                        $ary_access_key_valid[$i] = $application_info[$i]['ACCESS_KEY'];
                    } elseif (date('Ymd') <= date('Ymd', strtotime($application_info[$i]['EXPIRE_DATE']))) {
                        $ary_access_key_valid[$i] = $application_info[$i]['ACCESS_KEY'];
                    }
                }

                // セッションにアクセスキーを保存する。
                session()->put('ACCESS_KEY', $ary_access_key);
                session()->put('ACCESS_KEY_VALID', $ary_access_key_valid);
            }

            return $ary_app_data;
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }
    }

    /**
     * アプリケーション利用期限を延長する。
     *
     * @throws \Exception
     */
    public function extendExpiration()
    {
        try {
            $request = request();
            $access_key = $request->input('access_key');
            $api_user_id = session()->get('api_user_id');

            try {
                $user = $this->getUserInfoByApiUserId($api_user_id);
            } catch (\Exception $ex) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_09);
            }

            // セッションに保存されているアクセスキーを確認する。
            $result = $this->checkValidAccessKey($access_key);
            if (! $result) {
                throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
            }

            // 操作中のアプリケーションの情報を取得する。
            $app_infor = $this->getInforAppTarget($api_user_id, $access_key);
            if ($app_infor) {
                $expire_date = $app_infor->EXPIRE_DATE;
                $service_status = $app_infor->SERVICE_STATUS;
                $service_in = $app_infor->SERVICE_IN;
            }

            // API_PRODUCTSテーブルアップデート
            DB::beginTransaction();

            $this->extend($api_user_id, $access_key, $expire_date);

            $this->registLogDb($api_user_id, $user->userId, $service_status, $service_in);

            $this->sendMailExtension($user->eMail, $user->userName, $expire_date, $access_key);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }


    /**
     * 操作中のアプリケーションの情報を取得する。
     *
     * @param $api_user_id ユーザーのID
     * @param
     *            $access_key
     */
    private function getInforAppTarget($api_user_id, $access_key)
    {
        try {
            $ary_column = [
                'UP_URL_STATUS',
                'SERVICE_STATUS',
                'SERVICE_IN',
                'EXPIRE_DATE'
            ];
            $app_infor = ApiProductsModel::where('API_USER_ID', $api_user_id)
                ->where('ACCESS_KEY', $access_key)->first($ary_column);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        if (! $app_infor) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        } else {
            $expire_date = $app_infor['EXPIRE_DATE'];
            $timestamp_registered = strtotime(date("Y-m-d", strtotime($expire_date)));
            $timestamp_current = strtotime(date("Y-m-d"));
            $timestamp_month = strtotime(date("Y-m-d", strtotime('+30 day')));

            if ($timestamp_registered < $timestamp_current ||
                $timestamp_registered > $timestamp_month ||
                $app_infor['UP_URL_STATUS'] == 1) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
            }

            return $app_infor;
        }
    }

    /**
     * DBでアプリケーション利用期限を延長する。
     *
     * @param $api_user_id ログイン情報を認証する。
     * @param $access_key フォームからアクセスキーを渡す。
     * @param $expire_date アプリケーション利用期限
     */
    private function extend($api_user_id, $access_key, $expire_date)
    {
        try {
            $expire_timestamp = strtotime($expire_date);
            $expire_date_update = date('Y/m/d H:i:s', strtotime(CommonConst::LIMIT_EXPIRE_DATE, $expire_timestamp));
            $result = ApiProductsModel::where('API_USER_ID', $api_user_id)->where('ACCESS_KEY', $access_key)->update([
                'UPDATE_USER' => $api_user_id,
                'EXPIRE_DATE' => $expire_date_update
            ]);
        } catch (\Exception $ex) {
            if (preg_match('/^[Duplicate entry]*/', $ex->getMessage())) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_09);
            }

            throw new \Exception(ErrorCodeConst::ERR_CODE_45);
        }

        return $result;
    }

    /**
     * ログを記録する。
     *
     * @param $api_user_id ユーザーのID
     * @param $user_id ユーザー名
     * @param
     *            $service_status
     * @param
     *            $service_in
     */
    private function registLogDb($api_user_id, $user_id, $service_status, $service_in)
    {
        try {
            $result = ApiUserModel::where('API_USER_ID', $api_user_id)->first([
                'REGISTERED_DTIME'
            ]);

            $change_log = LogConst::LOG_APP_EXTENSION . ($service_status + $service_in);

            /**
             * @var $change_log_model ApiChangeLogModel
             */
            $change_log_model = app(ApiChangeLogModel::class);
            $change_log_model->API_USER_ID = $api_user_id;
            $change_log_model->CHANGE_LOG = $change_log;
            $change_log_model->LOG_USER = $user_id;
            $change_log_model->OPERATION_USER = $user_id;
            $change_log_model->REGISTRATION_DATE = $result['REGISTERED_DTIME'];
            $ret = $change_log_model->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_45);
        }
    }

    /**
     * 遅延成功通知メールを送信する。
     *
     * @param $mail ユーザーのメールアドレス
     * @param $user_name ユーザー名
     * @param $expire_date アプリケーション利用期限
     * @param $access_key フォームからアクセスキーを渡す。
     */
    private function sendMailExtension($mail, $user_name, $expire_date, $access_key)
    {
        try {
            /**
             * @var $send_mail SendMail
             */
            $send_mail = app(SendMail::class);
            $expire_timestamp = strtotime($expire_date);
            $expire_date = date('Y年m月d日', strtotime(CommonConst::LIMIT_EXPIRE_DATE, $expire_timestamp));

            $body = view('mail.mail_expand_expiary', [
                'name' => $user_name,
                'expire_date' => $expire_date,
                'access_key' => $access_key
            ])->render();

            $subject = MailConst::MAIL_KEY_EXPIRY_EXTENSION;
            $ret = $send_mail->send($subject, $mail, $body);

            if ($ret === false) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_45);
        }
    }
}
