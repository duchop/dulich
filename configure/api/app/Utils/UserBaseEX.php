<?php
namespace App\Utils;

use Illuminate\Support\Facades\Log;

class UserBaseEX
{

    /* UserBaseEX処理に成功の場合返すコード */
    const RESPONSE_200 = 200;

    /* UserBaseEX処理に失敗の場合返すコード */
    const RESPONSE_600 = 600;

    /*
     * UserBaseEX
     *
     * @var GUserBaseEX_ApiManager $api_manager
     */
    private $api_manager;

    /**
     * UserBaseEX初期化
     */
    public function __construct()
    {
        require_once('/home/www/gnavilib/_gUserBase/php/ex/library/include.php');
        require_once('/home/www/gnavilib/_gUserBase/php/library/GUserBaseMngInclude.php');

        $this->api_manager = new \GUserBaseEX_ApiManager('webservice01', 'webservice01');
    }

    /**
     * userIDでユーザの情報が存在するかチェック
     *
     * @param
     *            $user_id
     */
    public function checkExistUserId($user_id)
    {
        $ary_find_params = [
            'gwsUser' => [
                [
                    'userId',
                    $user_id,
                    GUE_CONDITION_EQUAL
                ]
            ]
        ];
        $ary_output_fields = [
            'gwsUser' => [
                'apiUserId',
                'userId'
            ]
        ];

        $result = $this->api_manager->selectDataList('gwsUser', $ary_find_params, $ary_output_fields);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($result, true));
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_600 || $response->state == self::RESPONSE_200) {
            return $result;
        } else {
            Log::error($response);
            return false;
        }
    }

    /**
     * メールでユーザの情報が存在するかチェック
     *
     * @param
     *            $email
     */
    public function checkExistEmail($email)
    {
        $ary_find_params = [
            'gwsUser' => [
                [
                    'eMail',
                    $email,
                    GUE_CONDITION_EQUAL
                ]
            ]
        ];
        $ary_output_fields = [
            'gwsUser' => [
                'apiUserId',
                'eMail'
            ]
        ];

        $result = $this->api_manager->selectDataList('gwsUser', $ary_find_params, $ary_output_fields);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($result, true));
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_600 || $response->state == self::RESPONSE_200) {
            return $result;
        } else {
            Log::error($response);
            return false;
        }
    }

    /**
     * IDでユーザ情報取得処理
     *
     * @param
     *            $api_user_id
     */
    public function getUserInfoByApiUserId($api_user_id)
    {
        $ary_find_params = [
            'gwsUser' => [
                [
                    'apiUserId',
                    $api_user_id,
                    GUE_CONDITION_EQUAL
                ]
            ]
        ];
        $ary_output_fields = [
            'gwsUser' => [
                'apiUserId',
                'userId',
                'userName',
                'eMail',
                'acceptMailMagazine',
                'loginPassword',
            ]
        ];

        $result = $this->api_manager->selectDataList('gwsUser', $ary_find_params, $ary_output_fields);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($result, true));
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_600 || $response->state == self::RESPONSE_200) {
            return $result;
        } else {
            Log::error($response);
            return false;
        }
    }

    /**
     * IDで会社情報取得処理
     *
     * @param
     *            $api_user_id
     */
    public function getCorporateUserInfoByApiUserId($api_user_id)
    {
        $ary_find_params = [
            'gwsUser' => [
                [
                    'apiUserId',
                    $api_user_id,
                    GUE_CONDITION_EQUAL
                ]
            ]
        ];
        $ary_output_fields = [
            'gwsCorporateUser' => [
                'corporationName',
                'department'
            ]
        ];

        $result = $this->api_manager->selectDataList('gwsUser', $ary_find_params, $ary_output_fields);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($result, true));
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_600 || $response->state == self::RESPONSE_200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * メールでユーザ情報取得処理
     *
     * @param
     *            $email
     */
    public function getUserInfoByEmail($email)
    {
        $ary_find_params = [
            'gwsUser' => [
                [
                    'eMail',
                    $email,
                    GUE_CONDITION_EQUAL
                ]
            ]
        ];
        $ary_output_fields = [
            'gwsUser' => [
                'apiUserId',
                'userId',
                'userName',
                'eMail',
                'loginPassword',
                'acceptMailMagazine'
            ]
        ];

        $result = $this->api_manager->selectDataList('gwsUser', $ary_find_params, $ary_output_fields);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($result, true));
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_600 || $response->state == self::RESPONSE_200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * パスワード取得処理
     *
     * @param
     *            $email
     * @param
     *            $user_id
     */
    public function getUserPassword($email, $user_id)
    {
        $ary_find_params = [
            'gwsUser' => [
                [
                    'eMail',
                    $email,
                    GUE_CONDITION_EQUAL
                ],
                [
                    'userId',
                    $user_id,
                    GUE_CONDITION_EQUAL
                ]
            ]
        ];
        $ary_output_fields = [
            'gwsUser' => [
                'apiUserId',
                'loginPassword',
                'userName'
            ]
        ];

        $result = $this->api_manager->selectDataList('gwsUser', $ary_find_params, $ary_output_fields);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($result, true));
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_600 || $response->state == self::RESPONSE_200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * ユーザ情報登録処理
     *
     * @param
     *            $ary_user_info
     * @return boolean
     */
    public function insertPersonalUserInfo($ary_user_info)
    {
        $ary_data_insert = [
            'gwsUser' => [
                'apiUserId' => $ary_user_info['api_user_id'] ?? '',
                'userId' => $ary_user_info['user_id'] ?? '',
                'userName' => $ary_user_info['user_name'] ?? '',
                'eMail' => $ary_user_info['e_mail'] ?? '',
                'loginPassword' => $ary_user_info['login_password'] ?? '',
                'acceptMailMagazine' => $ary_user_info['accept_mail_magazine'] ?? 0
            ]
        ];

        $this->api_manager->insertDataList($ary_data_insert);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 会社情報登録処理
     *
     * @param
     *            $ary_user_info
     * @return boolean
     */
    public function insertCorporateUserInfo($ary_user_info)
    {
        $ary_data_insert = [
            'gwsUser' => [
                'apiUserId' => $ary_user_info['api_user_id'] ?? '',
                'userId' => $ary_user_info['user_id'] ?? '',
                'userName' => $ary_user_info['user_name'] ?? '',
                'eMail' => $ary_user_info['e_mail'] ?? '',
                'loginPassword' => $ary_user_info['login_password'] ?? '',
                'acceptMailMagazine' => $ary_user_info['accept_mail_magazine'] ?? 0
            ],
            'gwsCorporateUser' => [
                [
                    'corporationName' => $ary_user_info['corporation_name'] ?? '',
                    'department' => $ary_user_info['department'] ?? ''
                ]
            ]
        ];

        $this->api_manager->insertDataList($ary_data_insert);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ユーザ情報変更処理
     *
     * @param
     *            $ary_user_info
     * @return boolean
     */
    public function updatePersonalUserInfo($ary_user_info)
    {
        $ary_data = [];

        if (isset($ary_user_info['api_user_id'])) {
            $ary_data['apiUserId'] = $ary_user_info['api_user_id'];
        } else {
            return false;
        }

        if (isset($ary_user_info['user_id'])) {
            $ary_data['userId'] = $ary_user_info['user_id'];
        }
        if (isset($ary_user_info['user_name'])) {
            $ary_data['userName'] = $ary_user_info['user_name'];
        }
        if (isset($ary_user_info['e_mail'])) {
            $ary_data['eMail'] = $ary_user_info['e_mail'];
        }
        if (isset($ary_user_info['login_password'])) {
            $ary_data['loginPassword'] = $ary_user_info['login_password'];
        }
        if (isset($ary_user_info['accept_mail_magazine'])) {
            $ary_data['acceptMailMagazine'] = $ary_user_info['accept_mail_magazine'];
        }

        if (count($ary_data) > 0) {
            $ary_data_update = [
                'gwsUser' => $ary_data
            ];

            $this->api_manager->updateDataList($ary_data_update);
            $response = $this->api_manager->getHeaderByResponse();
            Log::error(print_r($response, true));

            if ($response->state == self::RESPONSE_200) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 会社情報変更処理
     *
     * @param
     *            $ary_user_info
     * @return boolean
     */
    public function updateCorporateUserInfo($ary_user_info)
    {
        $ary_data_user = [];

        if (isset($ary_user_info['api_user_id'])) {
            $ary_data_user['apiUserId'] = $ary_user_info['api_user_id'];
        } else {
            return false;
        }

        if (isset($ary_user_info['user_id'])) {
            $ary_data_user['userId'] = $ary_user_info['user_id'];
        }
        if (isset($ary_user_info['user_name'])) {
            $ary_data_user['userName'] = $ary_user_info['user_name'];
        }
        if (isset($ary_user_info['e_mail'])) {
            $ary_data_user['eMail'] = $ary_user_info['e_mail'];
        }
        if (isset($ary_user_info['login_password'])) {
            $ary_data_user['loginPassword'] = $ary_user_info['login_password'];
        }
        if (isset($ary_user_info['accept_mail_magazine'])) {
            $ary_data_user['acceptMailMagazine'] = $ary_user_info['accept_mail_magazine'];
        }

        $ary_data_corporate = [
            'handleId' => GUE_DATAHANDLEID_UPDATE
        ];
        if (isset($ary_user_info['corporation_name'])) {
            $ary_data_corporate['corporationName'] = $ary_user_info['corporation_name'];
        }
        if (isset($ary_user_info['department'])) {
            $ary_data_corporate['department'] = $ary_user_info['department'];
        }

        $ary_data_update = [
            'gwsUser' => $ary_data_user,
            'gwsCorporateUser' => [
                $ary_data_corporate
            ]
        ];

        $this->api_manager->updateDataList($ary_data_update);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ユーザ情報削除処理
     *
     * @param
     *            $api_user_id
     * @return boolean
     */
    public function deletePersonalUserInfo($api_user_id)
    {
        $ary_data = [
            'gwsUser' => [
                'apiUserId' => $api_user_id
            ]
        ];
        $this->api_manager->deleteDataList($ary_data);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 会社情報削除処理
     *
     * @param
     *            $api_user_id
     * @return boolean
     */
    public function deleteCorporateUserInfo($api_user_id)
    {
        $ary_data = [
            'gwsUser' => [
                'apiUserId' => $api_user_id
            ],
            'gwsCorporateUser' => [
                [
                    'handleId' => GUE_DATAHANDLEID_DELETE
                ]
            ]
        ];
        $this->api_manager->deleteDataList($ary_data);
        $response = $this->api_manager->getHeaderByResponse();
        Log::error(print_r($response, true));

        if ($response->state == self::RESPONSE_200) {
            return true;
        } else {
            return false;
        }
    }
}
