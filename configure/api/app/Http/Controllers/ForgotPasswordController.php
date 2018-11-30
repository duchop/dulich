<?php
namespace App\Http\Controllers;

use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use App\Exceptions\ApiException;
use App\Http\Services\ForgotPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{

    /**
     * サービス
     *
     * @var ForgotPasswordService $sforgot_password_service
     */
    private $forgot_password_service;

    public function __construct()
    {
        $this->forgot_password_service = app(ForgotPasswordService::class);
    }

    /**
     * パスワード送信画面の制御
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            return view('mail_check');
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_08,
                'msg' => ErrorCodeConst::ERR_CODE_09
            ]);
        }
    }

    /**
     * パスワードをお忘れの方画面を制御する。
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doSendPass(Request $request)
    {
        try {
            $validator = $this->doValidate($request, $this->rules($request));

            // バリデーションエラーがある。
            if ($validator->fails()) {
                return view('mail_check', [
                    'user_id' => $request->input('user_id'),
                    'mail' => $request->input('mail')
                ])->withErrors($validator);
            }

            $this->forgot_password_service->sendPass();

            return view('send_pass');
        } catch (ApiException $e) {
            return view('mail_check', [
                'user_id' => $request->input('user_id'),
                'mail' => $request->input('mail'),
                'msg' => $e->getMessage()
            ]);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_08,
                'msg' => ErrorCodeConst::ERR_CODE_09
            ]);
        }
    }

    /**
     * ルール
     *
     * @return array
     */
    private function rules($request)
    {
        $ary_rule = [
            'user_id' => [
                'required' => CommonConst::LABEL_USER_ID . ErrorCodeConst::ERR_CODE_05
            ],
            'mail' => [
                'required' => CommonConst::LABEL_EMAIL . ErrorCodeConst::ERR_CODE_05
            ]
        ];

        $user_id = $request->input('user_id');
        $mail = $request->input('mail');

        // user_idとメールの存在を確認する。
        if (isset($user_id) && isset($mail)) {
            $ary_rule['user_id'] = array_merge($ary_rule['user_id'], [
                'user_id' => CommonConst::LABEL_USER_ID . ErrorCodeConst::ERR_CODE_12
            ]);

            $ary_rule['mail'] = array_merge($ary_rule['mail'], [
                'max:200' => CommonConst::LABEL_EMAIL . CommonConst::VARCHAR_200 . ErrorCodeConst::ERR_CODE_23,
                'email' => CommonConst::LABEL_EMAIL . ErrorCodeConst::ERR_CODE_07
            ]);
        }

        return $ary_rule;
    }
}
