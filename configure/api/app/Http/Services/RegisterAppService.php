<?php
namespace App\Http\Services;

use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use App\Constants\LogConst;
use App\Constants\MailConst;
use App\Http\Models\ApiChangeLogModel;
use App\Http\Models\ApiProductsModel;
use App\Http\Models\ApiUserModel;
use App\Utils\SendMail;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;

class RegisterAppService extends Service
{

    private $access_key;

    private $user;

    private $access_key_forder;

    private $registered_dtime;

    /**
     * NganVV_COMMENT_CODE_0123
     *
     * @var SendMail $send_mail
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
     * アプリ新規登録用入力画面を表示する条件を確認する。
     *
     * @throws \Exception
     */
    public function appRegistInput()
    {
        $api_user_id = session()->get('api_user_id');

        try {
            // CONTENTS NUMを確認する。
            $api_user = ApiUserModel::where([
                'API_USER_ID' => $api_user_id,
                'STATUS' => 1
            ])->get([
                'CONTENTS_NUM'
            ])->first();

            if (! $api_user) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        if ($api_user->CONTENTS_NUM >= 10) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_16);
        }
    }

    /**
     * アプリケーションを新規登録する。
     *
     * @param
     *            $postData
     * @return bool
     * @throws ApiException
     */
    public function appRegistComp()
    {
        try {
            $post_data = request();
            $api_user_id = session()->get('api_user_id');

            DB::beginTransaction();

            $this->updateInfoUser($api_user_id);

            // アクセスキーを作成する。
            $this->createAccessKey($api_user_id);

            // アプリケーションを登録する。
            $this->registApp($api_user_id, $post_data);

            // ユーザー基本EXでユーザー情報を取得する。
            try {
                $this->setUser($this->getUserInfoByApiUserId($api_user_id));
            } catch (\Exception $ex) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_08);
            }

            // ログを登録する。
            $this->registLog($api_user_id);

            // アクセスキーのファイルを作成する。
            $this->createAccessKeyFile($post_data);

            $this->sendMail($post_data);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }

        return true;
    }

    /**
     * URLの重複チェック
     *
     * @param String $url
     * @return boolean
     */
    public function checkExistUrl($url)
    {
        try {
            // URLの存在チェック
            $url = ApiProductsModel::where([
                'UP_URL' => $url
            ])->get([
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
     * 試用アプリの数を数える
     *
     * @return int
     * @throws \Exception
     */
    public function countTrialApp()
    {
        try {
            $api_user_id = session()->get('api_user_id');

            $products = ApiProductsModel::where([
                'API_USER_ID' => $api_user_id,
                'SERVICE_STATUS' => 1
            ])->get([
                'API_PRODUCTS_ID'
            ]);

            return count($products);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }
    }

    /**
     * 登録済みのアプリ数と試用のアプリ数を確認する。
     *
     * @param
     *            $apiUserId
     * @throws Exception
     */
    public function checkLimitApp($api_user_id)
    {
        try {
            $products = ApiProductsModel::where([
                'API_USER_ID' => $api_user_id
            ])->get([
                'API_PRODUCTS_ID'
            ]);

            if (! $products) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        if (count($products) >= 10) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_16);
        }
    }

    /**
     * アプリケーションを登録したユーザー情報を更新する。
     *
     * @param
     *            $apiUserId
     * @return bool
     * @throws \Exception
     */
    private function updateInfoUser($api_user_id)
    {
        try {
            $api_user = ApiUserModel::where([
                'API_USER_ID' => $api_user_id
            ])->get([
                'CONTENTS_NUM',
                'REGISTERED_DTIME'
            ])->first();

            $api_user->CONTENTS_NUM = $api_user->CONTENTS_NUM + 1;
            $ret = $api_user->save();

            $this->setRegisteredDtime($api_user->REGISTERED_DTIME);

            if (! $ret || empty($api_user)) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return true;
    }

    /**
     * アクセサリーのファイル名を作成する。
     *
     * @param
     *            $apiUserId
     * @return bool
     * @throws \Exception
     */
    private function createAccessKey($api_user_id)
    {
        try {
            while (true) {
                $access_key = md5($api_user_id . CommonConst::ACCESS_KEY . date(CommonConst::FORMAT_DATE));

                $products = ApiProductsModel::where([
                    'ACCESS_KEY' => $access_key
                ])->get([
                    'API_PRODUCTS_ID'
                ]);

                if (! $products) {
                    throw new \Exception();
                } elseif (count($products) < 1) {
                    $this->setAccessKey($access_key);
                    break;
                }
                sleep(1);
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return true;
    }

    /**
     * アプリケーションの情報を登録する。
     *
     * @param
     *            $apiUserId
     * @return bool
     * @throws \Exception
     */
    private function registApp($api_user_id, $post_data)
    {
        try {
            if ($post_data->service_status == 3) {
                if ($post_data->service_in == 1) {
                    $contents_name = $post_data->contents_name;
                    $up_url = $post_data->url;
                    $expire_status = 0;
                } else {
                    $contents_name = $post_data->contents_name ?? null;
                    $up_url = $post_data->url ?? null;
                    $expire_status = 1;
                }
                $contents_description = $post_data->contents_description ?? null;
                $contents_type = $post_data->contents_type ?? null;
                $service_in = $post_data->service_in;
            } else {
                $up_url = null;
                $contents_name = null;
                $expire_status = 1;
                $contents_type = null;
                $service_in = null;
                $contents_description = $post_data->contents_description ?? null;
            }

            if ($expire_status == 0) {
                $expire_date = null;
            } else {
                $expire_date = date(CommonConst::DATE_FORMAT_FULL, strtotime(CommonConst::LIMIT_EXPIRE_DATE));
            }

            $product = app(ApiProductsModel::class);
            $product->API_USER_ID = $api_user_id;
            $product->UP_URL = $up_url;
            $product->UP_URL_STATUS = CommonConst::UP_URL_STATUS_0;
            $product->CONTENTS_NAME = $contents_name;
            $product->CONTENTS_TYPE = $contents_type;
            $product->CONTENTS_DESCRIPTION = $contents_description;
            $product->REGIST_USER = $api_user_id;
            $product->UPDATE_USER = $api_user_id;
            $product->SERVICE_STATUS = $post_data->service_status;
            $product->SERVICE_IN = $service_in;
            $product->EXPIRE_STATUS = $expire_status;
            $product->EXPIRE_DATE = $expire_date;
            $product->ACCESS_KEY = $this->getAccessKey();
            $ret = $product->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return true;
    }

    /**
     * アプリケーションのログを登録する。
     *
     * @param
     *            $apiUserId
     * @return bool
     * @throws \Exception
     */
    private function registLog($api_user_id)
    {
        try {
            $user_id = $this->getUser()->userId;
            $registered_dtime = $this->getRegisteredDtime();

            $change_log_model = app(ApiChangeLogModel::class);
            $change_log_model->API_USER_ID = $api_user_id;
            $change_log_model->CHANGE_LOG = LogConst::LOG_APP_REGIST;
            $change_log_model->LOG_USER = $user_id;
            $change_log_model->OPERATION_USER = $user_id;
            $change_log_model->REGISTRATION_DATE = $registered_dtime;
            $ret = $change_log_model->save();

            if (! $ret) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return true;
    }

    /**
     * アクセスキーのファイルを作成する。
     *
     * @return bool
     * @throws \Exception
     */
    private function createAccessKeyFile($post_data)
    {
        $access_key = $this->getAccessKey();
        $md5dir = Util::getMD5Hash($access_key);
        $this->setAccessKeyFolder($md5dir);

        // アクセスキーを格納しているフォルダを作成する。
        if (! file_exists(CommonConst::ACCESS_KEY_DIR . $md5dir)) {
            if (! mkdir(CommonConst::ACCESS_KEY_DIR . $md5dir, CommonConst::MODE_CREATE_FOLDER)) {
                throw new \Exception(ErrorCodeConst::ERR_CODE_46);
            }
        }

        try {
            // ファイルを作成して、ファイル内容を追加する。
            if (! $fp = fopen(CommonConst::ACCESS_KEY_DIR . $md5dir . '/' . $access_key,
                CommonConst::MODE_OPEND_FILE)) {
                throw new \Exception();
            } else {
                $service_status = $post_data->service_status + $post_data->service_in;

                if (strcmp($service_status, CommonConst::STATUS_SERVICEIN) == 0) {
                    $limit = CommonConst::LIMIT_SERVICEIN;
                    $restrict_unit = CommonConst::RESTRICTUNIT_SERVICEIN;
                } else {
                    $limit = CommonConst::LIMIT_OTHER;
                    $restrict_unit = CommonConst::RESTRICTUNIT_OTHER;
                }

                $write_status = fwrite($fp, $service_status . ',' . $limit . ',' . $restrict_unit);

                if (! $write_status) {
                    throw new \Exception();
                }

                fclose($fp);
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return true;
    }

    /**
     * ユーザーのメールアドレスへメールを送信する。
     *
     * @return bool
     * @throws \Throwable
     */
    private function sendMail($post_data)
    {
        try {
            $access_key = $this->getAccessKey();
            $user = $this->getUser();

            $access_key_folder = $this->getAccessKeyFolder();

            $body = view('mail.mail_add_accesskey', [
                'name' => $user->userName,
                'contents_name' => $post_data->contents_name ?? CommonConst::DEFAULT_CONTENTS_NAME,
                'access_key' => $access_key
            ])->render();

            $subject = MailConst::MAIL_APP_REGIST_SUBJECT;

            // 仮認証キーをメール送信
            $ret = $this->send_mail->send($subject, $user->eMail, $body);

            if (! $ret) {
                if (file_exists(CommonConst::ACCESS_KEY_DIR . $access_key_folder . '/' . $access_key)) {
                    unlink(CommonConst::ACCESS_KEY_DIR . $access_key_folder . '/' . $access_key);
                }
                throw new \Exception(ErrorCodeConst::ERR_CODE_08);
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return true;
    }

    /**
     * NganVV_COMMENT_CODE_0105
     */
    public function getAccessKey()
    {
        return $this->access_key;
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
     * NganVV_COMMENT_CODE_0103
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * NganVV_COMMENT_CODE_0102
     *
     * @param Object $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * NganVV_COMMENT_CODE_0101
     */
    public function getAccessKeyFolder()
    {
        return $this->access_key_forder;
    }

    /**
     * NganVV_COMMENT_CODE_0100
     *
     * @param string $access_key_folder
     */
    public function setAccessKeyFolder($access_key_folder)
    {
        $this->access_key_forder = $access_key_folder;
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
