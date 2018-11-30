<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Constants\LogConst;
use App\Http\Models\ApiChangeLogModel;
use App\Http\Models\ApiProductsModel;
use App\Constants\CommonConst;
use App\Http\Models\ApiUserModel;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;
use App\Constants\MailConst;
use App\Utils\SendMail;
use App\Http\Models\ApiConfUrlModel;
use App\Http\Models\ApiProductsNgStatusModel;

class MaintainAppService extends Service
{

    private $user_id;

    private $user_name;

    private $mail;

    /**
     * API使用状況確認画面に表示するデータを取得するサービス
     *
     * @throws Exception
     * @return Object URLに表示するデータ
     */
    public function appCheckInput()
    {
        try {
            $ary_request = request()->all();
            $conf_date = date("Ymd");

            $api_conf_url = $this->selectApiConfUrlInfo($ary_request, $conf_date);

            $api_products = $this->selectProductsInfo($api_conf_url['API_PRODUCTS_ID']);

            // 画面に表示するデータを初期化する。
            $result = new \stdClass();

            $edate = explode(' ', $api_products['EXPIRE_DATE']);
            $result->expire_date = $edate[0];
            $result->service_status = $api_products['SERVICE_STATUS'];
            $result->service_in = $api_products['SERVICE_IN'];
            $result->contents_name = $api_products['CONTENTS_NAME'];
            $result->contents_description = $api_products['CONTENTS_DESCRIPTION'];
            $result->access_key = $api_products['ACCESS_KEY'];
            $result->up_url = $api_products['UP_URL'];
            $result->contents_type = $api_products['CONTENTS_TYPE'];
            $result->expire_status = $api_products['EXPIRE_STATUS'];
            $result->api_conf_id = $api_conf_url['API_CONF_ID'];
            $result->api_products_id = $api_conf_url['API_PRODUCTS_ID'];

            return $result;
        } catch (\Exception $ex) {
            throw $ex;
        }

        return;
    }

    /**
     * API使用状況確認完了サービス
     *
     * @throws \Exception
     */
    public function appCheckComp()
    {
        try {
            $ary_request = request()->all();
            $conf_date = date("Ymd");

            $api_conf_url = $this->selectApiConfUrlInfo($ary_request, null);

            $api_products = $this->selectProductsInfo($api_conf_url['API_PRODUCTS_ID']);

            // トランザクションスタート
            \DB::beginTransaction();

            $this->updateProductsNgStatus($conf_date, $api_conf_url['API_CONF_ID']);

            $this->updateApiConfUrl($api_conf_url['API_CONF_ID']);

            if (! $this->appCheckSendMail($api_products['ACCESS_KEY'], $api_products['API_USER_ID'])) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_08);
            }

            \DB::commit();
        } catch (\Exception $ex) {
            // ロールバック
            \DB::rollback();
            throw $ex;
        }

        return;
    }

    /**
     * URL情報を取得する関数
     *
     * @param array $ary_request
     *            リクエストデータの配列
     * @param date $conf_date
     *            現在日付
     * @throws \Exception
     * @return ApiConfUrlModel
     */
    private function selectApiConfUrlInfo($ary_request, $conf_date)
    {
        $pass_query = $ary_request['pass_query'];
        try {
            if (empty($pass_query)) {
                throw new \Exception();
                return;
            }

            $ret = ApiConfUrlModel::where('PASSQUERY', $pass_query)->first();

            if (empty($ret)) {
                throw new \Exception();
                return;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_16);
        }

        try {
            if ($ret['CONF_URL_STATUS'] == 0) {
                if (isset($conf_date) && ! empty($conf_date)) {
                    $urld = explode('_', $ret['API_CONF_ID']);
                    if (strtotime($urld[0] . CommonConst::LIMIT_API_CONF_URL) <= strtotime($conf_date)) {
                        throw new \Exception();
                        return;
                    }
                }

                return $ret;
            } else {
                throw new \Exception();
                return;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_43);
        }
    }

    /**
     * プロダクト情報を取得する関数
     *
     * @param int $products_id
     * @throws \Exception
     * @return ApiProductsModel
     */
    private function selectProductsInfo($products_id)
    {
        try {
            if (empty($products_id)) {
                throw new \Exception();
            }

            $ret = ApiProductsModel::where('API_PRODUCTS_ID', $products_id)->first();

            if (empty($ret)) {
                throw new \Exception();
                return;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        if ($ret['UP_URL_STATUS'] != 0) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_44);
            return;
        }

        return $ret;
    }

    /**
     * ユーザー情報を取得する関数
     *
     * @return ユーザー情報
     */
    private function selectApiUserInfo($api_user_id)
    {
        try {
            $result = $this->getUserInfoByApiUserId($api_user_id);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        return $result;
    }

    /**
     * API使用状況確認URLを保存する関数
     *
     * @param date $conf_date
     *            現在日付
     * @throws Exception
     */
    private function updateProductsNgStatus($conf_date, $api_conf_id)
    {
        if (! $api_conf_id) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_16);
        }

        try {
            $ret = ApiProductsNgStatusModel::where('API_CONF_ID', $api_conf_id)->update([
                'API_CONF_DATE' => $conf_date,
                'API_CONF_EXTENTION' => 1
            ]);

            if (empty($ret)) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * APIのURLを更新する関数
     *
     * @throws Exception
     */
    private function updateApiConfUrl($api_conf_id)
    {
        if (! $api_conf_id) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_16);
        }

        try {
            $ret = ApiConfUrlModel::where('API_CONF_ID', $api_conf_id)->where('CONF_URL_STATUS', 0)->update([
                'CONF_URL_STATUS' => 1
            ]);

            if (empty($ret)) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * API使用状況確認完了の通知メールを送信する関数
     *
     * @param string $access_key
     *            アプリケーションのアクセスをロックする。
     * @return boolean true：メール送信成功、false：その他
     */
    private function appCheckSendMail($access_key, $api_user_id)
    {
        try {
            $api_user = $this->selectApiUserInfo($api_user_id);

            $body = $body = view('mail.mail_appcheck', [
                'name' => $api_user->userName,
                'access_key' => $access_key
            ])->render();

            $subject = MailConst::MAIL_APP_CHECK_DONE_SUBJECT;

            /**
             *
             * @var $action SendMail
             */
            $action = app(SendMail::class);
            // ール送信
            $ret = $action->send($subject, $api_user->eMail, $body);

            if ($ret === false) {
                return false;
            }

            return true;
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * アプリケーション情報の削除を確認する関数
     *
     * @throws Exception
     * @return アプリケーション情報
     */
    public function appDeleteConf()
    {
        // クッキーのapi_user_idとセッションのapi_user_idが同じことを確認する
        $ary_request = request()->all();
        $ary_request = array_add($ary_request, 'api_user_id', session()->get('api_user_id'));
        session()->put('target_key', '');

        $access_key = $this->checkAccessKey($ary_request['access_key'], $ary_request['p']);

        if ($access_key == false) {
            throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
            return;
        }

        session()->put('target_key', $access_key);

        $api_user_id = $ary_request['api_user_id'];

        try {
            // 登録情報取得
            $ary_column = [
                'API_PRODUCTS_ID',
                'CONTENTS_NAME',
                'CONTENTS_DESCRIPTION',
                'CONTENTS_TYPE',
                'UP_URL',
                'SERVICE_STATUS',
                'SERVICE_IN',
                'EXPIRE_DATE',
                'ACCESS_KEY',
                'RESTRICTION_RELAXATION'
            ];

            $ret = ApiProductsModel::where('API_USER_ID', $api_user_id)
                                        ->where('ACCESS_KEY', $access_key)->first($ary_column);

            if (empty($ret)) {
                throw new \Exception();
                return false;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        // 取得情報整形
        $result = new \stdClass();
        $result->access_key = $access_key;
        $result->contents_name = $ret['CONTENTS_NAME'];
        $result->contents_type = $ret['CONTENTS_TYPE'];
        $result->url = $ret['UP_URL'];
        $result->contents_description = $ret['CONTENTS_DESCRIPTION'];
        $result->service_status = $ret['SERVICE_STATUS'];
        $result->service_in = $ret['SERVICE_IN'];
        $result->expire_date = $ret['EXPIRE_DATE'];
        $result->service_status = $ret['SERVICE_STATUS'];

        return $result;
    }

    /**
     * アプリケーションを削除する関数
     *
     * @throws \Exception
     */
    public function appDeleteComp()
    {
        try {
            // クッキーのapi_user_idとセッションのapi_user_idが同じことを確認する
            $ary_request = request()->all();
            $ary_request = array_add($ary_request, 'api_user_id', session()->get('api_user_id'));

            try {
                $access_key = $this->checkAccessKey($ary_request['access_key'], $ary_request['p']);
                if ($access_key == false) {
                    throw new \Exception();

                    return;
                }
            } catch (\Exception $ex) {
                throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
            }

            // トランザクションスタート
            \DB::beginTransaction();

            $this->deleteDb($ary_request);
            $this->deleteAccessKey($access_key);

            $this->sendMail($ary_request);

            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
    }

    /**
     * アプリケーションを削除し、削除ログを記録する。
     *
     * @param array $ary_request
     *            リクエストするパラメータの配列
     * @throws \Exception
     */
    private function deleteDb($ary_request)
    {
        $access_key = session()->get('target_key');
        $api_user_id = $ary_request['api_user_id'];

        // API_PRODUCTSテーブルアップデート
        try {
            $ret = ApiProductsModel::where('API_USER_ID', $api_user_id)->where('ACCESS_KEY', $access_key)->delete();

            if (empty($ret)) {
                throw new \Exception();
            }

            // API_USERテーブル追加
            $ary_column = [
                'CONTENTS_NUM',
                'REGISTERED_DTIME'
            ];

            $ret = ApiUserModel::where('API_USER_ID', $api_user_id)->first($ary_column);

            if (empty($ret)) {
                throw new \Exception();
            }

            $contents_num = $ret['CONTENTS_NUM'] - 1;
            $registered_dtime = $ret['REGISTERED_DTIME'];

            $ret = ApiUserModel::where('API_USER_ID', $api_user_id)->update([
                'CONTENTS_NUM' => $contents_num
            ]);

            if (empty($ret)) {
                throw new \Exception();
            }

            // ユーザー基本EXからユーザー情報を取得する。
            $result = $this->getUserInfoByApiUserId($api_user_id);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_42);
        }

        $this->setUserId($result->userId);
        $this->setUserName($result->userName);
        $this->setMail($result->eMail);

        try {
            // API_CHANGE_LOGテーブルインサート
            /**
             * @var $api_change_log ApiChangeLogModel
             */
            $api_change_log = app(ApiChangeLogModel::class);
            $api_change_log->API_USER_ID = $api_user_id;
            $api_change_log->CHANGE_LOG = str_replace_array('?', [
                $ary_request['service_status'] + $ary_request['service_in']
            ], LogConst::LOG_APP_DELETE);
            $api_change_log->LOG_USER = $this->getUserId();
            $api_change_log->OPERATION_USER = $this->getUserId();
            $api_change_log->REGISTRATION_DATE = $registered_dtime;
            $ret = $api_change_log->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        return;
    }

    /**
     * アプリケーションのアクセスキーを削除する関数
     *
     * @param string $access_key
     * @throws \Exception
     */
    private function deleteAccessKey($access_key)
    {
        $md5hash = Util::getMD5Hash($access_key);
        $acckey_file = CommonConst::ACCESS_KEY_DIR . $md5hash . "/" . $access_key;

        try {
            if (file_exists($acckey_file)) {
                if (!unlink($acckey_file)) {
                    throw new \Exception();
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_42);
        }

        return;
    }

    /**
     * アプリケーション削除成功の通知メールを送信する関数
     *
     * @param array $ary_request
     *            リクエストするパラメータの配列
     * @throws \Exception
     */
    private function sendMail($ary_request)
    {
        $access_key = session()->get('target_key');
        $contents_name = $ary_request['contents_name'];

        if (empty($contents_name)) {
            $contents_name = CommonConst::DEFAULT_CONTENTS_NAME;
        }

        $body = view('mail.mail_delete_accesskey', [
            'name' => $this->getUserName(),
            'contents_name' => $contents_name,
            'access_key' => $access_key
        ])->render();

        $subject = MailConst::MAIL_APP_DELETE_SUBJECT;

        /**
         *
         * @var $action SendMail
         */
        $action = app(SendMail::class);

        // アクセスキーをメール送信
        $ret = $action->send($subject, $this->getMail(), $body);

        if ($ret === false) {
            // アクセスキーファイル削除
            $md5hash = Util::getMD5Hash($access_key);
            unlink(CommonConst::ACCESS_KEY_DIR . $md5hash . "/" . $access_key);
            throw new \Exception(ErrorCodeConst::ERR_CODE_42);

            return;
        }

        return;
    }

    /**
     * アクセスキーの妥当性を確認する関数
     *
     * @param string $access_key
     * @param string $page
     * @return boolean/acces key
     */
    private function checkAccessKey($access_key, $page)
    {
        if ($page === CommonConst::PG_CONF) {
            if (count(session()->get('ACCESS_KEY')) >= 1) {
                if (in_array($access_key, session()->get('ACCESS_KEY'))) {
                    return $access_key;
                }
            } else {
                return false;
            }
        } elseif (strcmp(session()->get('target_key'), $access_key) == 0) {
            return $access_key;
        }

        return false;
    }

    /**
     * NganVV_COMMENT_CODE_0092
     *
     * @param String $user_name
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * NganVV_COMMENT_CODE_0093
     *
     * @return String
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * NganVV_COMMENT_CODE_0094
     *
     * @param string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * NganVV_COMMENT_CODE_0095
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * NganVV_COMMENT_CODE_0096
     *
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * NganVV_COMMENT_CODE_0097
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
}
