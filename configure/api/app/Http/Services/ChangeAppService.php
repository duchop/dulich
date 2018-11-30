<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Http\Models\ApiProductsModel;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Http\Models\ApiUserModel;
use App\Http\Models\ApiProductsNgStatusModel;
use App\Http\Models\ApiChangeLogModel;
use App\Constants\CommonConst;
use App\Utils\Util;
use App\Http\Models\ApiConfUrlModel;

class ChangeAppService extends Service
{

    private $error;

    /**
     * アプリ情報更新ボタンを押す場合
     *
     * @throws \Exception
     */
    public function appChangeInput()
    {
        $ary_post_data = request()->all();
        if (is_null($ary_post_data['access_key']) || is_null($ary_post_data['service_status'])) {
            throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
        }

        $access_key = $this->checkValidAccessKey($ary_post_data['access_key']);
        if ($access_key == false) {
            throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
        }

        // アプリ情報更新画面に戻る
        if (empty($ary_post_data['mypage'])) {
            throw new ApiException(ErrorCodeConst::ERR_CHECK_DATA);
        }

        $api_user_id = session()->get('api_user_id');

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
            $app = $this->getAppInfor($ary_column, $api_user_id, $access_key);
        } catch (\Exception $ex) {
            throw $ex;
        }

        // 取得情報整形
        $ary_post_data['access_key'] = $app['ACCESS_KEY'];
        $ary_post_data['contents_name'] = $app['CONTENTS_NAME'];
        $ary_post_data['contents_type'] = $app['CONTENTS_TYPE'];
        $ary_post_data['url'] = $app['UP_URL'];
        $ary_post_data['contents_description'] = $app['CONTENTS_DESCRIPTION'];
        $ary_post_data['service_status'] = $app['SERVICE_STATUS'];
        $ary_post_data['service_in'] = $app['SERVICE_IN'];
        $ary_post_data['expire_date'] = $app['EXPIRE_DATE'];

        return $ary_post_data;
    }

    /**
     * アプリ情報更新確認ボタンを押す場合
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function appChangeConf()
    {
        try {
            $ary_post_data = request()->all();
            $api_user_id = session()->get('api_user_id');

            $access_key = $this->checkValidAccessKey($ary_post_data['access_key']);

            if ($access_key == false) {
                throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
            }
            $ary_column = [
                'SERVICE_STATUS'
            ];

            $app = $this->getAppInfor($ary_column, $api_user_id, $ary_post_data['access_key']);
            $this->checkServiceStatus($ary_post_data['service_status'], $app['SERVICE_STATUS']);
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * アプリ情報更新完了ボタンを押す場合
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function appChangeComp()
    {
        try {
            $ary_post_data = request()->all();
            $api_user_id = session()->get('api_user_id');

            $access_key = $this->checkValidAccessKey($ary_post_data['access_key']);

            if ($access_key == false) {
                throw new \Exception(ErrorCodeConst::ERR_CHECK_DATA);
            }

            // 登録情報取得
            $ary_column = [
                'API_PRODUCTS_ID',
                'SERVICE_STATUS',
                'SERVICE_IN',
                'RESTRICTION_RELAXATION'
            ];

            $app = $this->getAppInfor($ary_column, $api_user_id, $ary_post_data['access_key']);
            $service_status = $app['SERVICE_STATUS'];

            $this->checkServiceStatus($ary_post_data['service_status'], $service_status);

            DB::beginTransaction();

            $this->changeDb($api_user_id, $ary_post_data, $app);

            $this->putFile($api_user_id, $ary_post_data['access_key'], $ary_post_data);

            DB::commit();
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }

        return;
    }

    /**
     * 変更重複チェック
     *
     * @param String $url
     * @param String $access_key
     * @return boolean
     */
    public function checkExistUrl($url, $access_key)
    {
        try {
            $ret = ApiProductsModel::where([
                'UP_URL' => $url
            ])->first([
                'ACCESS_KEY',
                'UP_URL'
            ]);

            if ($ret['ACCESS_KEY'] != $access_key) {
                if ($ret['UP_URL'] == $url) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }
    }

    /**
     * DBにアプリ情報更新
     *
     * @param $api_user_id ユーザーのID
     * @param $ary_post_data フォームデータ
     * @throws \Exception
     */
    private function changeDb($api_user_id, $ary_post_data, $app)
    {
        $api_products_id = $app['API_PRODUCTS_ID'];

        try {
            // API_PRODUCTSテーブルアップデート
            $ary_changes = $this->change($api_user_id, $ary_post_data, $app['EXPIRE_DATE'], $api_products_id);

            if ($app['SERVICE_STATUS'] == 3 && $app['SERVICE_IN'] == 1 && $ary_post_data['service_in'] == 0) {
                // API_PRODUCTS_NG_STATUSのCheckStatusを無効に変更
                $api_product_ng_status = ApiProductsNgStatusModel::where('API_PRODUCTS_ID', $api_products_id)
                    ->where('CHECK_STATUS', 0)->update(['CHECK_STATUS' => 99, 'UPDATE_USER' => $api_user_id]);

                // API_CONF_URLのCONF_URL_STATUSを無効に変更
                $api_conf_url = ApiConfUrlModel::where('API_PRODUCTS_ID', $api_products_id)
                    ->where('CONF_URL_STATUS', 0)->update(['CONF_URL_STATUS' => 4, 'UPDATE_USER' => $api_user_id]);

                if (! $api_product_ng_status || ! $api_conf_url) {
                    throw new \Exception();
                }
            }

            // DB更新ログ登録
            $user_info = $this->getUserInfoByApiUserId($api_user_id);

            $result = ApiUserModel::where('API_USER_ID', $api_user_id)->first([
                'REGISTERED_DTIME'
            ]);

            if (! $result) {
                throw new \Exception();
            }

            array_pull($ary_changes, 'UPDATE_USER');
            array_pull($ary_changes, 'UPDATE_DTIME');

            if (count($ary_changes) != 0) {
                $app = $this->getAppInfor(array_keys($ary_changes), $api_user_id, $ary_post_data['access_key']);
            }

            foreach ($ary_changes as $key => $value) {

                /**
                 *
                 * @var $change_log_model ApiChangeLogModel
                 */
                $change_log_model = app(ApiChangeLogModel::class);
                $change_log_model->API_USER_ID = $api_user_id;
                $change_log_model->CHANGE_LOG = $key . ',' . $app[$key] . ',' . $value;
                $change_log_model->LOG_USER = $user_info->userId;
                $change_log_model->OPERATION_USER = $user_info->userId;
                $change_log_model->REGISTRATION_DATE = $result['REGISTERED_DTIME'];
                $ret = $change_log_model->save();

                if (! $ret) {
                    throw new \Exception();
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }
    }

    /**
     * API_PRODUCTSテーブルに更新
     *
     * @param $api_user_id ユーザーのID
     * @param $ary_post_data フォームデータ
     * @param $expire_date アプリケーション利用期限
     * @throws Exception
     */
    private function change($api_user_id, $ary_post_data, $expire_date, $api_products_id)
    {
        try {
            if ($ary_post_data['service_status'] == 3) {
                if ($ary_post_data['service_in'] == 1) {
                    $contents_name = $ary_post_data['contents_name'];
                    $up_url = $ary_post_data['url'];
                    $expire_status = 0;
                } else {
                    $contents_name = $ary_post_data['contents_name'] ?? null;
                    $up_url = $ary_post_data['url'] ?? null;
                    $expire_status = 1;
                }
                $contents_description = $ary_post_data['contents_description'] ?? null;
                $contents_type = $ary_post_data['contents_type'] ?? null;
                $service_in = $ary_post_data['service_in'];
            } else {
                $up_url = null;
                $contents_name = null;
                $expire_status = 1;
                $contents_type = null;
                $service_in = null;
                $contents_description = $ary_post_data['contents_description'] ?? null;
            }

            if ($expire_status == 0) {
                $expire_date = null;
            } else {
                if (! isset($expire_date)) {
                    $expire_date = date(CommonConst::DATE_FORMAT_FULL, strtotime(CommonConst::LIMIT_EXPIRE_DATE));
                }
            }

            $product = ApiProductsModel::where('API_PRODUCTS_ID', $api_products_id)->first();
            $product['UP_URL'] = $up_url;
            $product['SERVICE_STATUS'] = $ary_post_data['service_status'];
            $product['SERVICE_IN'] = $service_in;
            $product['EXPIRE_STATUS'] = $expire_status;
            $product['CONTENTS_NAME'] = $contents_name;
            $product['CONTENTS_TYPE'] = $contents_type;
            $product['CONTENTS_DESCRIPTION'] = $contents_description;
            $product['UPDATE_USER'] = $api_user_id;
            $product['EXPIRE_DATE'] = $expire_date;
            $ret = $product->save();

            $ary_changes = $product->getChanges();
        } catch (\Exception $ex) {
            throw new \Exception();
        }

        if (! $ret) {
            throw new \Exception();
        }

        return $ary_changes;
    }

    /**
     * アプリ種類チェック
     *
     * @param $service_status フォームのアプリ情報
     * @throws ApiException
     */
    private function checkServiceStatus($service_status, $service_status_db)
    {
        $ary_error = [];

        if ($service_status_db == 2 && $service_status == 1) {
            $ary_error['service_status'] = ErrorCodeConst::ERR_CODE_21;
        }

        if ($service_status_db == 3) {
            if ($service_status == 1 || $service_status == 2) {
                $ary_error['service_status'] = ErrorCodeConst::ERR_CODE_21;
            }
        }

        if (! empty($ary_error)) {
            $this->setDataError($ary_error);

            throw new ApiException(ErrorCodeConst::ERR_CHECK_DATA);
        }

        return;
    }

    /**
     * アプリ情報の情報
     *
     * @param
     *            $ary_colum
     * @param
     *            $api_user_id
     * @param
     *            $access_key
     * @throws \Exception
     */
    private function getAppInfor($ary_colum, $api_user_id, $access_key)
    {
        try {
            $app = ApiProductsModel::where('API_USER_ID', $api_user_id)
                                    ->where('ACCESS_KEY', $access_key)->first($ary_colum);
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        if (! $app) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        return $app;
    }

    /**
     * アクセスキーファイル
     *
     * @param $api_user_id ユーザーのID
     * @param $access_key アプリのアクセスキー
     * @throws \Exception
     */
    private function putFile($api_user_id, $access_key, $ary_post_data)
    {
        try {
            $md5dir = Util::getMD5Hash($access_key);

            $ary_column = [
                'RESTRICTION_RELAXATION'
            ];
            $result = ApiProductsModel::where('API_USER_ID', $api_user_id)
                                        ->where('ACCESS_KEY', $access_key)->first($ary_column);

            if (! $fp = fopen(CommonConst::ACCESS_KEY_DIR . $md5dir . "/" . $access_key, "w")) {
                throw new \Exception();
            } else {
                $service_status = $ary_post_data['service_status'] + $ary_post_data['service_in'];
                if (strcmp($service_status, CommonConst::STATUS_SERVICEIN) == 0) {
                    if ($result['RESTRICTION_RELAXATION'] == 0) {
                        $limit = CommonConst::LIMIT_SERVICEIN;
                    } else {
                        $limit = $result['RESTRICTION_RELAXATION'];
                    }
                    $restrict_unit = CommonConst::RESTRICTUNIT_SERVICEIN;
                } else {
                    $limit = CommonConst::LIMIT_OTHER;
                    $restrict_unit = CommonConst::RESTRICTUNIT_OTHER;
                }

                $write_status = fwrite($fp, $service_status . "," . $limit . "," . $restrict_unit);
                if (! $write_status) {
                    throw new \Exception();
                }
                fclose($fp);
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_08);
        }

        return;
    }

    /**
     * NganVV_COMMENT_CODE_0089
     *
     * @param array $error
     */
    public function setDataError($error)
    {
        $this->error = $error;
    }

    /**
     * NganVV_COMMENT_CODE_0090
     *
     * @return array NganVV_COMMENT_CODE_0091
     */
    public function getDataError()
    {
        return $this->error;
    }
}
