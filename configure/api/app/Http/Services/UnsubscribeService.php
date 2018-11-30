<?php
namespace App\Http\Services;

use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use App\Constants\LogConst;
use App\Constants\MailConst;
use App\Http\Models\ApiChangeLogModel;
use App\Http\Models\ApiCorporageUserInfoModel;
use App\Http\Models\ApiIndivisualUserInfoModel;
use App\Http\Models\ApiProductsModel;
use App\Http\Models\ApiUserDisabledModel;
use App\Http\Models\ApiUserModel;
use App\Exceptions\ApiException;
use App\Http\Models\ApiUserUnsubscribeModel;
use App\Utils\CookieChecker;
use App\Utils\SendMail;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;

class UnsubscribeService extends Service
{

    private $api_user_id;

    private $api_user;

    private $list_access_key;

    private $registered_dtime;

    /**
     * NganVV_COMMENT_CODE_0123
     *
     * @var $send_mail SendMail
     */
    protected $send_mail;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->send_mail = app(SendMail::class);
    }

    /**
     * 退会完了画面の制御
     *
     * @return bool
     * @throws ApiException
     * @throws \Throwable
     */
    public function unsubscribeComp()
    {
        try {
            $post_data = request();
            $api_user_id = session()->get('api_user_id');
            $this->setApiUserId($api_user_id);

            // ユーザが存在しているか確認する。
            $this->checkUserExist($post_data);

            // アプリ情報とユーザ情報を取得する。
            $this->getAppInfor();

            DB::beginTransaction();

            // 全てのユーザ情報を削除する。
            $this->deleteFromDb($post_data);

            // ログ登録を行う。
            $this->registLog();

            // ユーザー基本ＥＸでのユーザ情報を削除する。
            $this->delInforInUserBaseEX($post_data);

            DB::commit();

            // 全てのアクセスキーを削除する。
            $this->deleteListAccessKey();

            // メールを送信する。
            $this->sendMail();
        } catch (ApiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw $ex;
        }

        CookieChecker::sessionDestroy();

        return true;
    }

    /**
     * ユーザーの存在チェック
     *
     * @return bool
     * @throws ApiException
     */
    private function checkUserExist($post_data)
    {
        $api_user_id = $this->getApiUserId();

        // データベースからユーザ情報を取得する。
        try {
            $api_user = ApiUserModel::where([
                'API_USER_ID' => $api_user_id,
                'USER_TYPE' => $post_data->user_type,
                'STATUS' => 1
            ])->get([
                'API_USER_ID',
                'REGISTERED_DTIME'
            ])->first();

            if (! $api_user || count($api_user) < 1) {
                throw new \Exception();
            }

            $this->setRegisteredDtime($api_user->REGISTERED_DTIME);

            // ユーザー基本ＥＸでのユーザ情報を取得する。
            $ret = $this->getUserInfoByApiUserId($api_user_id);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_37);
        }

        // パスワードを暗号化する。
        $pass = Util::passDecryption($ret->loginPassword);

        // パスワードを比較する。
        if (strcmp($post_data->pass, $pass) != 0) {
            throw new ApiException(ErrorCodeConst::ERR_CODE_41);
        }

        $this->setApiUser($ret);

        return true;
    }

    /**
     * アプリケーション情報及びユーザー情報を取得する。
     *
     * @return bool
     */
    private function getAppInfor()
    {
        $api_user_id = $this->getApiUserId();

        // アプリのアクセスキー一覧を取得する。
        try {
            $list_access_key = ApiProductsModel::where([
                'API_USER_ID' => $api_user_id
            ])->get([
                'ACCESS_KEY'
            ]);

            if (! $list_access_key) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_37);
        }

        $this->setListAccessKey($list_access_key);

        return true;
    }

    /**
     * 退会ユーザーの情報をDBから削除する。
     *
     * @return bool
     */
    private function deleteFromDb($post_data)
    {
        $api_user_id = $this->getApiUserId();
        $registered_dtime = $this->getRegisteredDtime();

        // ユーザのログを削除する。
        try {
            // 退会ログを登録する。
            $this->registUserUnsubscribe($post_data, $registered_dtime);

            $api_change_log = ApiChangeLogModel::where([
                'API_USER_ID' => $api_user_id
            ])->delete();

            if ($api_change_log === false) {
                throw new \Exception();
            }

            // ＡＰＩ使用停止ユーザ管理データを削除する。
            $api_user_disabled_status = ApiUserDisabledModel::where([
                'API_USER_ID' => $api_user_id
            ])->delete();

            if ($api_user_disabled_status === false) {
                throw new \Exception();
            }

            // ユーザ情報を削除する。
            if ($post_data->user_type == 0) {
                $info = ApiIndivisualUserInfoModel::where([
                    'API_USER_ID' => $api_user_id
                ])->delete();

            } elseif ($post_data->user_type == 1) {
                $info = ApiCorporageUserInfoModel::where([
                    'API_USER_ID' => $api_user_id
                ])->delete();
            }

            if ($info === false) {
                throw new \Exception();
            }

            // ユーザのアプリを削除する。
            $api_product = ApiProductsModel::where([
                'API_USER_ID' => $api_user_id
            ])->delete();

            if ($api_product === false) {
                throw new \Exception();
            }

            // ユーザ情報を削除する。
            $user = ApiUserModel::find($api_user_id)->delete();

            if ($user === false) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_37);
        }

        return true;
    }

    /**
     * 退会理由を登録する。
     *
     * @param
     *            $post_data
     * @param
     *            $regist_dtime
     * @return bool
     */
    private function registUserUnsubscribe($post_data, $regist_dtime)
    {
        try {
            $reason01 = (isset($post_data->reason01)) ? 1 : 0;
            $reason02 = (isset($post_data->reason02)) ? 1 : 0;
            $reason03 = (isset($post_data->reason03)) ? 1 : 0;
            $reason04 = (isset($post_data->reason04)) ? 1 : 0;
            $reason05 = (isset($post_data->reason05)) ? 1 : 0;
            $reason09 = (isset($post_data->reason09)) ? 1 : 0;

            $api_user_unsub_scribe_model = app(ApiUserUnsubscribeModel::class);
            $api_user_unsub_scribe_model->REASON01 = $reason01;
            $api_user_unsub_scribe_model->REASON02 = $reason02;
            $api_user_unsub_scribe_model->REASON03 = $reason03;
            $api_user_unsub_scribe_model->REASON04 = $reason04;
            $api_user_unsub_scribe_model->REASON05 = $reason05;
            $api_user_unsub_scribe_model->REASON09 = $reason09;
            $api_user_unsub_scribe_model->MORE_REASON = $post_data->more_reason;
            $api_user_unsub_scribe_model->REGISTERED_DTIME = $regist_dtime;
            $api_user_unsub_scribe_model->UNSUB_DTIME = now();
            $ret = $api_user_unsub_scribe_model->save();
        } catch (\Exception $ex) {
            throw new \Exception();
        }

        if (! $ret) {
            throw new \Exception();
        }
    }

    /**
     * ユーザー基本EXからユーザーの情報を削除する。
     *
     * @param $post_data
     * @throws \Exception
     */
    private function delInforInUserBaseEX($post_data)
    {
        $api_user_id = $this->getApiUserId();

        try {
            $this->getUserInfoByApiUserId($api_user_id);

            $this->delInfoInUserBaseEX($post_data->user_type, $api_user_id);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_37);
        }
    }

    /**
     * ログ出力
     *
     * @return bool
     */
    private function registLog()
    {
        $api_user_id = $this->getApiUserId();
        $api_user = $this->getApiUser();

        try {
            $change_log_model = app(ApiChangeLogModel::class);
            $change_log_model->API_USER_ID = $api_user_id;
            $change_log_model->CHANGE_LOG = LogConst::LOG_UNSUBSCRIBE;
            $change_log_model->LOG_USER = $api_user->userId;
            $change_log_model->OPERATION_USER = $api_user->userId;
            $change_log_model->REGISTRATION_DATE = $this->getRegisteredDtime();
            $ret = $change_log_model->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_37);
        }

        return true;
    }

    /**
     * すべてのアクセスキーを削除する。
     *
     * @return bool
     */
    private function deleteListAccessKey()
    {
        $list_access_key = $this->getListAccessKey()->toArray();

        foreach ($list_access_key as $access_key) {
            $md5hash = Util::getMD5Hash($access_key['ACCESS_KEY']);
            $access_key_file = CommonConst::ACCESS_KEY_DIR . $md5hash . '/' . $access_key['ACCESS_KEY'];
            try {
                unlink($access_key_file);
            } catch (\Exception $ex) {
                // DO NOTHING
            }
        }

        return true;
    }

    /**
     * メールを送信する。
     *
     * @return bool
     * @throws \Throwable
     */
    private function sendMail()
    {
        try {
            $api_user = $this->getApiUser();

            $body = view('mail.mail_unsubscribe_done', [
                'name' => $api_user->userName
            ])->render();

            $subject = MailConst::MAIL_UNSUB_SUBJECT;

            // 仮認証キーをメール送信
            $ret = $this->send_mail->send($subject, $api_user->eMail, $body);

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_47);
        }

        return true;
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
     */
    public function getApiUserId()
    {
        return $this->api_user_id;
    }

    /**
     * NganVV_COMMENT_CODE_0109
     *
     * @param Object $api_user
     */
    public function setApiUser($api_user)
    {
        $this->api_user = $api_user;
    }

    /**
     * NganVV_COMMENT_CODE_0110
     *
     * @return Object
     */
    public function getApiUser()
    {
        return $this->api_user;
    }

    /**
     * NganVV_COMMENT_CODE_0126
     *
     * @return List
     */
    public function getListAccessKey()
    {
        return $this->list_access_key;
    }

    /**
     * NganVV_COMMENT_CODE_0125
     *
     * @param List $list_access_key
     */
    public function setListAccessKey($list_access_key)
    {
        $this->list_access_key = $list_access_key;
    }

    /**
     * NganVV_COMMENT_CODE_0099
     *
     * @return Date
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
