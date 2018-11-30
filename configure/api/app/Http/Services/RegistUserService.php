<?php
namespace App\Http\Services;

use App\Http\Models\ApiProductsModel;
use App\Constants\ErrorCodeConst;
use App\Http\Models\ApiUserModel;
use App\Utils\SendMail;
use App\Utils\UserBaseEX;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;
use App\Constants\CommonConst;
use App\Constants\LogConst;
use App\Constants\MailConst;
use App\Http\Models\ApiChangeLogModel;
use App\Http\Models\ApiCorporageUserInfoModel;
use App\Http\Models\ApiIndivisualUserInfoModel;
use App\Utils\ParseXML;

class RegistUserService extends Service
{

    private $api_user_id;

    private $key;

    private $approval_url;

    private $registered_dtime;

    /**
     *
     * @var ParseXML $xml_parse
     */
    private $xml_parse;

    private $xml;

    private $regist_user;

    private $access_key;

    private $md5dir;

    /**
     *
     * @var UserBaseEX $user_base_ex
     */
    private $user_base_ex;

    /**
     *
     * @var SendMail $send_mail
     */
    private $send_mail;

    public function __construct()
    {
        $this->user_base_ex = app(UserBaseEX::class);
        $this->send_mail = app(SendMail::class);
    }

    /**
     * URLの存在チェック
     *
     * @param String $url
     * @return boolean
     * @throws \Exception
     */
    public function checkExistUrl($url)
    {
        try {
            $url = ApiProductsModel::where('UP_URL', $url)->get([
                'ACCESS_KEY',
                'UP_URL'
            ]);

            if (count($url) > 0) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }
    }

    /**
     * user_idの存在チェック
     *
     * @param String $value
     * @return boolean
     * @throws \Exception
     */
    public function checkExistUserID($value)
    {
        try {
            $exit_user_id = $this->user_base_ex->checkExistUserId($value);

            if ($exit_user_id === false) {
                throw new \Exception();
                return;
            } elseif (! empty($exit_user_id)) {
                return true;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        return false;
    }

    /**
     * メールアドレスの存在チェック
     *
     * @param String $value
     * @return boolean
     * @throws \Exception
     */
    public function checkExistEmail($value)
    {
        try {
            $exit_email = $this->user_base_ex->checkExistEmail($value);

            if ($exit_email === false) {
                throw new \Exception();
                return;
            } elseif (! empty($exit_email)) {
                return true;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        return false;
    }

    /**
     * アカウントを新規登録する。
     *
     * @return bool
     * @throws ApiException
     * @throws \Throwable
     */
    public function registComp()
    {
        try {
            $post_data = request();
            $post_data->encodePath = Util::passEncryption($post_data->pass1);
            $post_data->user_name = $post_data->user_name1 . ' ' . $post_data->user_name2;

            DB::beginTransaction();
            $this->registDb($post_data);

            if (! $this->sendMail($post_data)) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_08);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            throw $ex;
        }
        return true;
    }

    /**
     * DB内の操作
     *
     * @throws \Exception
     */
    private function registDb($post_data)
    {
        try {
            // API_USERテーブルインサート
            $api_user_id = $this->registApiUser($post_data);

            if (! $api_user_id) {
                throw new \Exception();
            }

            $this->setApiUserId($api_user_id);

            // API_USER(個人or法人)テーブルインサート
            if ($post_data->user_type == 0) {
                $ret = $this->registIndivisualUserInfo($post_data);
            } elseif ($post_data->user_type == 1) {
                $ret = $this->registCorporageUserInfo($post_data);
            }

            if ($ret === false) {
                throw new \Exception();
            }

            // API_PRODUCTテーブル追加
            $ret = $this->registApiProduct($post_data, CommonConst::UP_URL_STATUS_1);

            if ($ret === false) {
                throw new \Exception();
            }

            $change_log_model = app(ApiChangeLogModel::class);
            $change_log_model->API_USER_ID = $api_user_id;
            $change_log_model->CHANGE_LOG = LogConst::LOG_TMP_REGIST;
            $change_log_model->LOG_USER = $post_data->user_id;
            $change_log_model->OPERATION_USER = $post_data->user_id;
            $change_log_model->REGISTRATION_DATE = $this->getRegisteredDtime();
            $ret = $change_log_model->save();

            if (! $ret) {
                throw new \Exception();
            }

            // ユーザー基本ＥＸでのユーザ情報を追加する。
            if ($post_data->user_type == 0) {
                $ret = $this->user_base_ex->insertPersonalUserInfo([
                    'api_user_id' => $api_user_id,
                    'user_id' => $post_data->user_id,
                    'user_name' => $post_data->user_name,
                    'e_mail' => $post_data->mail1,
                    'login_password' => $post_data->encodePath,
                    'accept_mail_magazine' => $post_data->accept_mail_magazine
                ]);
            } elseif ($post_data->user_type == 1) {
                $ret = $this->user_base_ex->insertCorporateUserInfo([
                    'api_user_id' => $api_user_id,
                    'corporation_name' => $post_data->corporation_name,
                    'department' => $post_data->department,
                    'user_id' => $post_data->user_id,
                    'user_name' => $post_data->user_name,
                    'e_mail' => $post_data->mail1,
                    'login_password' => $post_data->encodePath,
                    'accept_mail_magazine' => $post_data->accept_mail_magazine
                ]);
            }

            if (! $ret) {
                throw new \Exception();
            }

            if (! $this->makeTempKey($post_data)) {
                // ユーザー基本ＥＸロールバックを行う。

                $this->getUserInfoByApiUserId($api_user_id);

                $this->delInfoInUserBaseEX($post_data->user_type, $api_user_id);

                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * 登録
     *
     * @param Objects $post_data
     * @return String or ErrorMsg
     */
    private function registApiUser($post_data)
    {
        if ($post_data->foreign_status_k == 1 || $post_data->foreign_status_h == 1) {
            $foreign_status = 1;
        } else {
            $foreign_status = 0;
        }

        // 条件渡し
        $api_user = app(ApiUserModel::class);
        $api_user->STATUS = 2;
        $api_user->USER_TYPE = $post_data->user_type;
        $api_user->CONTENTS_NUM = 1;
        $api_user->FOREIGN_STATUS = $foreign_status;
        $ret = $api_user->save();

        // sql実行
        if (! $ret) {
            return false;
        }

        // SQLバインド
        $api_user->REGISTERED_DTIME = now();
        $api_user->REGIST_USER = $api_user->API_USER_ID;
        $api_user->UPDATE_USER = $api_user->API_USER_ID;
        $api_user->save();

        $this->setRegisteredDtime($api_user->REGISTERED_DTIME);

        // シーケンスの値を返す
        return $api_user->API_USER_ID;
    }

    /**
     * 登録
     *
     * @param Objects $post_data
     *            ポストデータ
     * @param string $up_url_status
     * @return String $seq シーケンスの値 or ErrorMsg
     */
    private function registApiProduct($post_data, $up_url_status)
    {
        $api_user_id = $this->getApiUserId();

        if (is_null($up_url_status)) {
            $up_url_status = CommonConst::UP_URL_STATUS_1;
        }

        if ($post_data->service_status == 3) {
            if ($post_data->service_in == 1) {
                $up_url = $post_data->url;
                $contents_name = $post_data->contents_name;
                $expire_status = 0;
            } else {
                $up_url = $post_data->url ? $post_data->url : null;
                $contents_name = $post_data->contents_name ? $post_data->contents_name : null;
                $expire_status = 1;
            }
            $contents_description = $post_data->contents_description ? $post_data->contents_description : null;
            $contents_type = $post_data->contents_type ? $post_data->contents_type : null;
            $service_in = $post_data->service_in;
        } else {
            $up_url = null;
            $contents_name = null;
            $expire_status = 1;
            $contents_type = null;
            $service_in = null;
            $contents_description = $post_data->contents_description ? $post_data->contents_description : null;
        }

        if ($expire_status == 0) {
            $expire_date = null;
        } else {
            $expire_date = date(CommonConst::DATE_FORMAT_FULL, strtotime(CommonConst::LIMIT_EXPIRE_DATE));
        }

        // 条件渡し
        $api_product = app(ApiProductsModel::class);
        $api_product->API_USER_ID = $api_user_id;
        $api_product->UP_URL = $up_url;
        $api_product->UP_URL_STATUS = $up_url_status;
        $api_product->CONTENTS_NAME = $contents_name;
        $api_product->CONTENTS_TYPE = $contents_type;
        $api_product->CONTENTS_DESCRIPTION = $contents_description;
        $api_product->REGIST_USER = $api_user_id;
        $api_product->UPDATE_USER = $api_user_id;
        $api_product->SERVICE_STATUS = $post_data->service_status;
        $api_product->SERVICE_IN = $service_in;
        $api_product->EXPIRE_STATUS = $expire_status;
        $api_product->EXPIRE_DATE = $expire_date;
        $ret = $api_product->save();

        // sql実行
        if ($ret) {
            return $api_product->API_PRODUCTS_ID;
        }

        return false;
    }

    /**
     * API_INDIVISUAL_USER_INFOを登録する。
     *
     * @param
     *            $post_data
     * @return bool
     */
    private function registIndivisualUserInfo($post_data)
    {
        $api_user_id = $this->getApiUserId();

        if ($post_data->foreign_status_k == 1) {
            $zip = null;
        } else {
            $zip = $post_data->zip_k_1 . $post_data->zip_k_2;
        }

        $api_indivisual_user_info = app(ApiIndivisualUserInfoModel::class);
        $api_indivisual_user_info->API_USER_ID = $api_user_id;
        $api_indivisual_user_info->ADDRESS_ZIP = $zip;
        $api_indivisual_user_info->REGIST_USER = $api_user_id;
        $api_indivisual_user_info->UPDATE_USER = $api_user_id;
        $ret = $api_indivisual_user_info->save();

        if ($ret) {
            return true;
        }

        return false;
    }

    /**
     * API CORPORATE USER INFOを登録する。
     *
     * @param
     *            $post_data
     * @return bool
     */
    private function registCorporageUserInfo($post_data)
    {
        $api_user_id = $this->getApiUserId();

        if ($post_data->foreign_status_h == 1) {
            $tel = null;
            $zip = null;
            $pref = null;
            $city = null;
            $street = null;
        } else {
            $tel = $post_data->tel_h_1 . '-' . $post_data->tel_h_2 . '-' . $post_data->tel_h_3;
            $zip = $post_data->zip_h_1 . $post_data->zip_h_2;
            $pref = $post_data->pref_h;
            $city = $post_data->city_h;
            $street = $post_data->street_h;
        }

        $api_corporage_user_info = app(ApiCorporageUserInfoModel::class);
        $api_corporage_user_info->API_USER_ID = $api_user_id;
        $api_corporage_user_info->PHONE_NO = $tel;
        $api_corporage_user_info->ADDRESS_ZIP = $zip;
        $api_corporage_user_info->ADDRESS_PREF = $pref;
        $api_corporage_user_info->ADDRESS_CITY = $city;
        $api_corporage_user_info->ADDRESS_STREET = $street;
        $api_corporage_user_info->REGIST_USER = $api_user_id;
        $api_corporage_user_info->UPDATE_USER = $api_user_id;
        $ret = $api_corporage_user_info->save();

        if ($ret) {
            return true;
        }

        return false;
    }

    /**
     * xmlのファイル名を作成する。
     *
     * @param
     *            $post_data
     * @return bool
     * @throws \Throwable
     */
    private function makeTempKey($post_data)
    {
        $mail = hash('sha256', $post_data->mail1 . CommonConst::PRIVATE_KEY);
        $url = hash('sha256', $post_data->url . CommonConst::PRIVATE_KEY);
        $time = microtime();
        $key = hash('sha256', $mail . $url . $time . CommonConst::PRIVATE_KEY);
        $this->setKey($key);
        $this->setApprovalUrl($key, $mail, $url, $time);
        $xml_body = $this->getXml($time, $post_data);

        if (! $this->putFile($xml_body)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * xmlファイルにデータを出力する。
     *
     * @param
     *            $time
     * @return string
     * @throws \Throwable
     */
    private function getXml($time, $post_data)
    {
        $api_user_id = $this->getApiUserId();

        // XMLオブジェクト作成
        $xml = new \stdClass();
        $xml->api_user_id = $api_user_id;
        $xml->mail = $post_data->mail1;
        $xml->url = htmlspecialchars($post_data->url);
        $xml->time = $time;

        $xml_body = view('key', [
            'xml' => $xml
        ])->render();

        $xml_body = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" . $xml_body;

        return $xml_body;
    }

    /**
     * xmlファイルを作成する。
     *
     * @param
     *            $xmlBody
     * @return bool
     */
    private function putFile($xml_body)
    {
        $key = $this->getKey();
        $ret = true;

        try {
            if (! $fp = fopen(CommonConst::XML_DIR . $key . '.xml', "w")) {
                $ret = false;
            }

            if ($ret) {
                if (flock($fp, LOCK_EX)) {
                    fwrite($fp, $xml_body);
                    flock($fp, LOCK_UN);
                } else {
                    $ret = false;
                }
            }

            if ($ret) {
                if (! fclose($fp)) {
                    $ret = false;
                }
            }

            if (! $ret) {
                return false;
            }
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    /**
     * メールを送信する。
     *
     * @return bool
     * @throws \Throwable
     */
    private function sendMail($post_data)
    {
        try {
            $key = $this->getKey();

            $body = view('mail.mail_key', [
                'name' => $post_data->user_name,
                'mail' => $post_data->mail1,
                'approval' => $this->getApprovalUrl()
            ])->render();
            $subject = MailConst::KEY_SUBJECT;

            // 仮認証キーをメール送信
            $ret = $this->send_mail->send($subject, $post_data->mail1, $body);

            if (! $ret) {
                if (file_exists(CommonConst::XML_DIR . $key . '.xml')) {
                    unlink(CommonConst::XML_DIR . $key . '.xml');
                }
                $api_user_id = $this->getApiUserId();

                $this->getUserInfoByApiUserId($api_user_id);

                $this->delInfoInUserBaseEX($post_data->user_type, $api_user_id);

                return false;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
        return true;
    }

    /**
     * NganVV_COMMENT_CODE_0124
     *
     * @throws \Exception
     * @throws Exception
     */
    public function approval()
    {
        try {
            $ary_request = request()->all();

            // xmlチェック
            $this->checkXml($ary_request);

            // トランザクションスタート
            \DB::beginTransaction();

            try {
                // 登録した情報を確認する。
                $this->checkRegist();

                // DBで登録されているアクセスキーを確認する。
                $this->checkAccessKey();

                // 正当なURLから渡されたパラメータを確認する。
                $this->checkUrl($ary_request);

                // ユーザーの本登録が完了した。
                $this->approvalDb();

                // ユーザー情報認証成功ログを登録する。
                $this->registLogDb(LogConst::LOG_REGIST);

                // アクセスキーのファイルを作成する。
                $this->putFileAccessKey();

                // 認証成功通知メールを送信する。
                $this->sendMailApproval();

                \DB::commit();
            } catch (\Exception $ex) {
                throw $ex;
            }

            $regist_user = $this->getRegistUserData();

            return $regist_user->user_name;
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
    }

    /**
     * XMLデータとリクエストデータが一致するか確認する関数
     *
     * @param array $ary_request
     * @throws \Exception
     */
    private function checkXml($ary_request)
    {
        try {
            // パラメータを受け取る
            if (empty($ary_request['key']) || empty($ary_request['m']) ||
                empty($ary_request['u']) || empty($ary_request['t'])) {
                throw new \Exception();
                return;
            }

            // 仮認証XMLファイル取得
            $xml_path = CommonConst::XML_DIR . $ary_request['key'] . '.xml';
            if (! file_exists($xml_path)) {
                throw new \Exception();
                return;
            }

            $xml_str = file_get_contents($xml_path);
            $this->xml_parse = app(ParseXML::class);
            $xml_array = $this->xml_parse->parse($xml_str);
            $xml = $this->xml_parse->parseKeyXML($xml_array);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_04);
        }

        $this->setParseXmlData($xml);
    }

    /**
     * DBに登録済みのユーザー情報を取得して確認する関数
     *
     * @throws \Exception
     *
     * @return void
     */
    private function checkRegist()
    {
        // 既に登録されているかチェック
        $ary_column = [
            'STATUS',
            'USER_TYPE',
            'REGISTERED_DTIME'
        ];

        try {
            $xml = $this->getParseXmlData();
            $ary_ret = ApiUserModel::where('API_USER_ID', $xml->api_user_id)->first($ary_column);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_01);
        }

        if (empty($ary_ret)) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_30);
        } elseif ($ary_ret['STATUS'] < 2) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_03);
        }

        /**
         *
         * @var $user_base UserBaseEX
         */
        $user_base = app(UserBaseEX::class);

        try {
            $result = $user_base->getUserInfoByApiUserId($xml->api_user_id);
            if ($result === false) {
                throw new \Exception();
                return;
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_01);
        }

        if (empty($result) || empty($result[0]->gwsUser)) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_30);
            return;
        }

        $regist_user = new \stdClass();
        $regist_user->user_id = $result[0]->gwsUser->userId;
        $regist_user->user_name = $result[0]->gwsUser->userName;
        $regist_user->user_type = $ary_ret['USER_TYPE'];
        $regist_user->registered_dtime = $ary_ret['REGISTERED_DTIME'];

        $xml = $this->getParseXmlData();
        $regist_user->api_user_id = $xml->api_user_id;

        $this->setRegistUserData($regist_user);

        return;
    }

    /**
     * アクセスキーを確認する関数
     *
     * @throws \Exception
     * @return void
     */
    private function checkAccessKey()
    {
        $ret = false;

        $xml = $this->getParseXmlData();
        try {
            while (! $ret) {
                // アクセスキー発行
                $access_key = md5($xml->api_user_id . CommonConst::ACCESS_KEY . date(CommonConst::FORMAT_DATE));
                $count = ApiProductsModel::where('ACCESS_KEY', $access_key)->get()->count();

                if ($count > 0) {
                    sleep(1);
                } else {
                    $ret = true;
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_01);
        }

        $this->setAccessKey($access_key);

        return;
    }

    /**
     * 認証用URLにリクエストされているデータが正しいか確認する関数
     *
     * @param array $ary_request
     *            URLから取得したリクエストデータ
     * @throws Exception
     * @return void
     */
    private function checkUrl($ary_request)
    {
        $xml = $this->getParseXmlData();

        // リンクの有効期限チェック
        $ary_time = explode(" ", $xml->time);

        if (count($ary_time) < 2) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_02);
            return;
        }

        if ((time() - $ary_time[1]) / (24 * 60 * 60) > CommonConst::EXPIRATION_DATE) {
            if ($this->delRecord()) {
                // 削除時はここでコミット
                \DB::commit();
            }

            throw new \Exception(ErrorCodeConst::ERR_CODE_02);
            return;
        }

        if (strlen($ary_request['m']) > CommonConst::LENGTH_SHA1) {
            $check_mail = hash('sha256', $xml->mail . CommonConst::PRIVATE_KEY);
        } else {
            $check_mail = sha1($xml->mail . CommonConst::PRIVATE_KEY);
        }

        $url = CommonConst::PRIVATE_KEY;

        if (isset($xml->url)) {
            $url = htmlspecialchars_decode($xml->url) . CommonConst::PRIVATE_KEY;
        }

        if (strlen($ary_request['m']) > CommonConst::LENGTH_SHA1) {
            $check_url = hash('sha256', $url);
        } else {
            $check_url = sha1($url);
        }

        if ($ary_request['m'] != $check_mail) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_04);
            return;
        }

        if ($ary_request['u'] != $check_url) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_04);
            return;
        }

        if (urldecode($ary_request['t']) != $xml->time) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_04);
            return;
        }
    }

    /**
     * 認証期限が切れたため、登録されたユーザー情報を削除する。
     *
     * @throws \Exception
     * @return boolean
     */
    private function delRecord()
    {
        $regist_user = $this->getRegistUserData();
        $api_user_id = $regist_user->api_user_id;

        try {
            $ret = ApiUserModel::where('API_USER_ID', $api_user_id)->delete();

            if ($ret === false) {
                throw new \Exception();
            }

            // API_USER(個人or法人)テーブル削除
            if ($ret) {
                if ($regist_user->user_type == 0) {
                    $ret = ApiIndivisualUserInfoModel::where('API_USER_ID', $api_user_id)->delete();
                } elseif ($regist_user->user_type == 1) {
                    $ret = ApiCorporageUserInfoModel::where('API_USER_ID', $api_user_id)->delete();
                }

                if ($ret === false) {
                    throw new \Exception();
                }
            }

            // ユーザのログデータを削除する。
            $ret = ApiChangeLogModel::where('API_USER_ID', $api_user_id)->delete();

            if (! $ret) {
                throw new \Exception();
            }

            // ユーザのアプリデータを削除する。
            $ret = ApiProductsModel::where('API_USER_ID', $api_user_id)->delete();

            if (! $ret) {
                throw new \Exception();
            }

            $this->registLogDb(LogConst::LOG_EXPIRED_DELETE);

            $ret = $this->delInfoInUserBaseEX($regist_user->user_type, $api_user_id);

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_28);
        }

        return true;
    }

    /**
     * 承認ログ、削除ログを記録する関数
     *
     * @param string $message
     *            message log
     * @throws Exception
     * @return boolean
     */
    private function registLogDb($message)
    {
        try {
            $regist_user = $this->getRegistUserData();

            // API_CHANGE_LOGテーブルインサート
            /**
             *
             * @var $api_change_log ApiChangeLogModel
             */
            $api_change_log = app(ApiChangeLogModel::class);
            $api_change_log->API_USER_ID = $regist_user->api_user_id;
            $api_change_log->CHANGE_LOG = $message;
            $api_change_log->LOG_USER = $regist_user->user_id;
            $api_change_log->OPERATION_USER = $regist_user->user_id;
            $api_change_log->REGISTRATION_DATE = $regist_user->registered_dtime;
            $ret = $api_change_log->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_28);
        }
    }

    /**
     * ユーザー登録用情報を認証する関数
     *
     * @throws \Exception
     */
    private function approvalDb()
    {
        $regist_user = $this->getRegistUserData();
        $access_key = $this->getAccessKey();
        $api_user_id = $regist_user->api_user_id;

        try {
            $ret = ApiUserModel::where('API_USER_ID', $api_user_id)->update([
                'STATUS' => 1, // ステータス1：承認
                'UPDATE_USER' => $api_user_id
            ]);

            if (! $ret) {
                throw new \Exception();
            }

            $ary_ret = $this->findApiProduct($api_user_id);

            $expire_status = $ary_ret['EXPIRE_STATUS'];
            $api_products_id = $ary_ret['API_PRODUCTS_ID'];

            if ($expire_status == 0) {
                $expire_date = null;
            } else {
                $expire_date = date('Y/m/d H:i:s', strtotime(CommonConst::LIMIT_EXPIRE_DATE));
            }

            $api_products_model = ApiProductsModel::find($api_products_id);
            $api_products_model['ACCESS_KEY'] = $access_key;
            $api_products_model['UP_URL_STATUS'] = CommonConst::UP_URL_STATUS_0;
            $api_products_model['UP_URL_STATUS_UPDATE_DTIME'] = now();
            $api_products_model['EXPIRE_DATE'] = $expire_date;
            $api_products_model['UPDATE_USER'] = $api_user_id;
            $ret = $api_products_model->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_01);
        }
    }

    /**
     * アクセスキーのファイルを作成する関数
     *
     * @throws Exception
     */
    private function putFileAccessKey()
    {
        $access_key = $this->getAccessKey();
        $md5dir = Util::getMD5Hash($access_key);

        $this->setMd5Dir($md5dir);
        $regist_user = $this->getRegistUserData();
        $api_user_id = $regist_user->api_user_id;

        if (! file_exists(CommonConst::ACCESS_KEY_DIR . $md5dir)) {
            if (! mkdir(CommonConst::ACCESS_KEY_DIR . $md5dir, CommonConst::MODE_CREATE_FOLDER)) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_46);
            }
        }

        if (! $fp = @fopen(CommonConst::ACCESS_KEY_DIR . $md5dir . "/" . $access_key, "w")) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_01);
        }

        $ary_ret = $this->findApiProduct($api_user_id, $access_key);

        $service_status = $ary_ret['SERVICE_STATUS'] + $ary_ret['SERVICE_IN'];
        if (strcmp($service_status, CommonConst::STATUS_SERVICEIN) == 0) {
            $limit = CommonConst::LIMIT_SERVICEIN;
            $restrict_unit = CommonConst::RESTRICTUNIT_SERVICEIN;
        } else {
            $limit = CommonConst::LIMIT_OTHER;
            $restrict_unit = CommonConst::RESTRICTUNIT_OTHER;
        }

        $write_status = fwrite($fp, $service_status . "," . $limit . "," . $restrict_unit);
        if (! $write_status) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
        fclose($fp);

        return;
    }

    /**
     * ユーザー登録成功の通知メールを送信する。
     *
     * @throws Excepton
     */
    private function sendMailApproval()
    {
        $regist_user = $this->getRegistUserData();
        $access_key = $this->getAccessKey();

        $body = view('mail.mail_accesskey', [
            'user_name' => $regist_user->user_name,
            'access_key' => $access_key
        ])->render();

        $subject = MailConst::ACCESS_KEY_SUBJECT;
        $xml = $this->getParseXmlData();
        $md5dir = $this->getMd5Dir();

        /**
         *
         * @var $action SendMail
         */
        $action = app(SendMail::class);

        // アクセスキーをメール送信
        $ret = $action->send($subject, $xml->mail, $body);

        if ($ret === false) {
            // アクセスキーファイル削除
            unlink(CommonConst::ACCESS_KEY_DIR . $md5dir . "/" . $access_key);

            throw new \Excepton(ErrorCodeConst::ERR_CODE_01);
            return;
        }

        return;
    }

    /**
     * NganVV_COMMENT_CODE_0127
     *
     * @param int $api_user_id
     * @param string $access_key
     * @return Object ApiProductsModel
     */
    private function findApiProduct($api_user_id, $access_key = null)
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
                'ACCESS_KEY',
                'RESTRICTION_RELAXATION',
                'EXPIRE_REASON_1',
                'EXPIRE_REASON_2',
                'EXPIRE_REASON_3',
                'EXPIRE_REASON_4',
                'EXPIRE_REASON_5'
            ];
            $ary_where_data = [];
            $ary_where_data[] = [
                'API_USER_ID',
                $api_user_id
            ];

            if (! empty($access_key)) {
                $ary_where_data[] = [
                    'ACCESS_KEY',
                    $access_key
                ];
            }

            $ret = ApiProductsModel::where($ary_where_data)->orderBy('REGIST_DTIME', 'ASC')->first($ary_column);

            if (! $ret) {
                throw new \Excepton();
            }
        } catch (\Excepton $ex) {
            throw new \Excepton(ErrorCodeConst::ERR_CODE_01);
        }

        return $ret;
    }

    /**
     * NganVV_COMMENT_CODE_0106
     *
     * @param string $xml
     */
    public function setParseXmlData($xml)
    {
        $this->xml = $xml;
    }

    /**
     * NganVV_COMMENT_CODE_0108
     *
     * @return string NganVV_COMMENT_CODE_0107
     */
    public function getParseXmlData()
    {
        return $this->xml;
    }

    /**
     * NganVV_COMMENT_CODE_0109
     *
     * @param Object $regist_user
     */
    public function setRegistUserData($regist_user)
    {
        $this->regist_user = $regist_user;
    }

    /**
     * NganVV_COMMENT_CODE_0110
     *
     * @return Object
     */
    public function getRegistUserData()
    {
        return $this->regist_user;
    }

    /**
     * NganVV_COMMENT_CODE_0104
     *
     * @param string $access_key
     */
    public function setAccessKey($access_key)
    {
        $this->access_key = $access_key;
    }

    /**
     * NganVV_COMMENT_CODE_0105
     *
     * @return string
     */
    public function getAccessKey()
    {
        return $this->access_key;
    }

    /**
     * NganVV_COMMENT_CODE_0111
     *
     * @param string $dd
     */
    public function setMd5Dir($dd)
    {
        $this->md5dir = $dd;
    }

    /**
     * NganVV_COMMENT_CODE_0112
     *
     * @return string
     */
    public function getMd5Dir()
    {
        return $this->md5dir;
    }

    /**
     * アカウントを有効化するメール
     *
     * @param
     *            $key
     * @param
     *            $mail
     * @param
     *            $url
     * @param
     *            $time
     */
    public function setApprovalUrl($key, $mail, $url, $time)
    {
        $this->approval_url = config('app.url') .
                                'approval/?key=' . $key . '&m=' . $mail .
                                '&u=' . $url . '&t=' . urlencode($time);
    }

    /**
     * NganVV_COMMENT_CODE_0117
     *
     * @return string
     */
    public function getApprovalUrl()
    {
        return $this->approval_url;
    }

    /**
     * NganVV_COMMENT_CODE_0118
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * NganVV_COMMENT_CODE_0119
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * NganVV_COMMENT_CODE_0120
     *
     * @param int $api_user_id
     */
    public function setApiUserId($api_user_id)
    {
        $this->api_user_id = $api_user_id;
    }

    /**
     * NganVV_COMMENT_CODE_0121
     *
     * @return int
     */
    public function getApiUserId()
    {
        return $this->api_user_id;
    }

    /**
     * NganVV_COMMENT_CODE_0099
     */
    public function getRegisteredDtime()
    {
        return $this->registered_dtime;
    }

    /**
     * NganVV_COMMENT_CODE_0098
     *
     * @param Date $registered_dtime
     */
    public function setRegisteredDtime($registered_dtime)
    {
        $this->registered_dtime = $registered_dtime;
    }
}
