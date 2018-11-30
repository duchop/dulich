<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Constants\MailConst;
use App\Exceptions\ApiException;
use App\Http\Models\ApiUserModel;
use App\Utils\SendMail;
use App\Utils\UserBaseEX;
use App\Utils\Util;

class ForgotPasswordService
{

    /**
     * NganVV_COMMENT_CODE_0122
     *
     * @var UserBaseEX $user_base_ex
     */
    private $user_base_ex;

    /**
     * NganVV_COMMENT_CODE_0123
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
     * パスワードを送信する。
     *
     * @return bool
     * @throws ApiException
     * @throws \Throwable
     */
    public function sendPass()
    {
        $post_data = request();

        // ユーザー基本ＥＸでのユーザ情報を取得する。
        try {
            $user = $this->user_base_ex->getUserPassword($post_data->mail, $post_data->user_id);

            if ($user === false) {
                throw new \Exception();
            }

            if (empty($user)) {
                throw new ApiException(ErrorCodeConst::ERR_CODE_11);
            }

            // ユーザのステータス情報を取得する。
            $api_user = ApiUserModel::where([
                'API_USER_ID' => $user[0]->gwsUser->apiUserId
            ])->get([
                'STATUS'
            ])->first();

            if ($api_user === false) {
                // エラーページ表示
                throw new \Exception();
            } elseif (! $api_user) {
                throw new ApiException(ErrorCodeConst::ERR_CODE_11);
            } else {
                if ($api_user->STATUS == 0) {
                    // エラーページ表示
                    throw new ApiException(ErrorCodeConst::ERR_CODE_31);
                } elseif ($api_user->STATUS == 2) {
                    throw new ApiException(ErrorCodeConst::ERR_CODE_32);
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_USER_BASE_EX);
        }

        $this->sendMail($post_data->mail, $user[0]);

        return true;
    }

    /**
     * ユーザにパスワードを含むメールを送信する。
     *
     * @param
     *            $mail
     * @return bool
     * @throws \Throwable
     */
    private function sendMail($mail, $user)
    {
        try {
            $body = view('mail.mail_id_pass', [
                'name' => $user->gwsUser->userName,
                'pass' => Util::passDecryption($user->gwsUser->loginPassword)
            ])->render();
            $subject = MailConst::ID_PASS_SUBJECT;

            // パスワードをメール送信
            $ret = $this->send_mail->send($subject, $mail, $body);

            if ($ret === false) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_SEND_MAIL);
        }
    }
}
