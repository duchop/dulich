<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\ErrorCodeConst;
use App\Http\Services\LoginService;
use App\Exceptions\ApiException;
use App\Constants\CommonConst;

class LoginController extends Controller
{

    /**
     * サービス
     *
     * @var LoginService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(LoginService::class);
    }

    /**
     * ログイン画面にアクセス処理
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        return view('index');
    }

    /**
     * ログインボタンを押すときの処理
     *
     * @param Request $request
     */
    public function doLogin(Request $request)
    {
        $validator = $this->doValidate($request, $this->rules());

        if ($validator->fails()) {
            return view('login')->withErrors($validator);
        }

        try {
            // ログイン情報の確認
            $this->service->login();

            return redirect()->secure(CommonConst::APP_MYPAGE);
        } catch (ApiException $e) {
            return view('login')->withErrors($e->getMessage());
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_05,
                'msg' => $ex->getMessage()
            ], []);
        }
    }

    /**
     * ルール
     *
     * @return array
     */
    private function rules()
    {
        return [
            'mail' => [
                'required' => CommonConst::LABEL_EMAIL_LOGIN . '：' . ErrorCodeConst::ERR_CODE_05,
                'email' => CommonConst::LABEL_EMAIL_LOGIN . '：' . ErrorCodeConst::ERR_CODE_07,
                'max:200' => CommonConst::LABEL_EMAIL_LOGIN . '： ' . CommonConst::VARCHAR_200 .
                    ErrorCodeConst::ERR_CODE_23
            ],
            'pass' => [
                'required' => CommonConst::LABEL_PASS_LOGIN . '：' . ErrorCodeConst::ERR_CODE_05,
                'password' => CommonConst::LABEL_PASS_LOGIN . '：' . ErrorCodeConst::ERR_CODE_36
            ]
        ];
    }
}
