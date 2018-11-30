<?php
namespace App\Http\Controllers;

use App\Utils\CookieChecker;
use Illuminate\Http\Request;
use App\Constants\ErrorCodeConst;
use App\Constants\CommonConst;
use Illuminate\Support\Facades\Log;
use App\Utils\Util;
use App\Http\Services\RegistUserService;

class RegisterUserController extends Controller
{

    /**
     * サービス
     *
     * @var RegistUserService $regist_user_service
     */
    private $regist_user_service;

    public function __construct()
    {
        $this->regist_user_service = app(RegistUserService::class);
    }

    /**
     * 全体の制御
     *
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        if ($request->input('p') == CommonConst::PG_INPUT) {
            return $this->registInput($request);
        } else {
            return view('error', [
                'msg' => ErrorCodeConst::ERR_CODE_16,
                'title' => ErrorCodeConst::ERR_TITLE_07
            ]);
        }
    }

    /**
     * アカウント新規登録画面を制御する。
     *
     * @param Request $request
     * @return view
     */
    public function doRegist(Request $request)
    {
        if ($request->input('p') == CommonConst::PG_CONF) {
            return $this->registConf($request);
        } elseif ($request->input('p') == CommonConst::PG_COMP) {
            return $this->registComp($request);
        } else {
            return view('error', [
                'msg' => ErrorCodeConst::ERR_CODE_16,
                'title' => ErrorCodeConst::ERR_TITLE_07
            ]);
        }
    }

    /**
     * ユーザー登録情報認証画面を初期化する関数
     *
     * @param Request $request
     * @return view
     */
    public function doApproval(Request $request)
    {
        try {
            $user_name = $this->regist_user_service->approval();

            return view('approval', ['userName' => $user_name], []);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_07,
                'msg' => $ex->getMessage()
            ], []);
        }
    }

    /**
     * 新規アカウント発行画面の制御
     *
     * @param Request $request
     * @return view
     */
    private function registInput($request)
    {
        try {
            $list_pref = $this->regist_user_service->getListPref();
            return view('regist_input')->with('prefList', $list_pref)->with('postData', $request);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error')->with('title', ErrorCodeConst::ERR_TITLE_07)->with('msg', $ex->getMessage());
        }
    }

    /**
     * 新規アカウント発行確認画面の制御
     *
     * @param Request $request
     * @return view
     */
    private function registConf($request)
    {
        try {
            $validator = $this->validateRegistUser($request);

            if ($validator->fails()) {
                $list_pref = $this->regist_user_service->getListPref();
                return view('regist_input')->with('postData', $request)
                    ->with('prefList', $list_pref)
                    ->withErrors($validator);
            }

            $request->escapePass = Util::escapePass($request->pass1);

            if ($request->service_status == 1 || $request->service_status == 2) {
                $request->contents_name = "";
                $request->contents_type = 0;
                $request->url = "";
                $request->expire_status = 1;
            } elseif ($request->service_status == 3) {
                $request->expire_status = 0;
            }

            return view('regist_conf')->with('postData', $request);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error')->with('title', ErrorCodeConst::ERR_TITLE_07)->with('msg', $ex->getMessage());
        }
    }

    /**
     * 新規アカウント発行完了画面の制御
     *
     * @param
     *            $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function registComp($request)
    {
        try {
            // データのバリデーションをチェックする
            $validator = $this->validateRegistUser($request);

            if ($validator->fails()) {
                $list_pref = $this->regist_user_service->getListPref();
                return view('regist_input')->with('postData', $request)
                    ->with('prefList', $list_pref)
                    ->withErrors($validator);
            }

            // アカウントを登録する。
            $this->regist_user_service->registComp();

            return view('regist_comp', [
                'name' => $request->user_name
            ]);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'msg' => $ex->getMessage(),
                'title' => ErrorCodeConst::ERR_TITLE_07
            ]);
        }
    }

    /**
     * データのバリデーションをチェックする
     *
     * @param
     *            $request
     * @return mixed
     */
    private function validateRegistUser($request)
    {
        $validator = $this->doValidate($request, $this->rulesRegist($request));

        $validator->after(function ($validator) use ($request) {
            if ($request->input('service_status') == 3 && ! empty($request->input('url')) &&
                $this->regist_user_service->checkExistUrl($request->input('url'))) {
                $validator->errors()->add('url', ErrorCodeConst::ERR_CODE_13);
            }

            if (! empty($request->input('user_id')) &&
                $this->regist_user_service->checkExistUserID($request->input('user_id'))) {
                $validator->errors()->add('user_id', ErrorCodeConst::ERR_CODE_13);
            }

            if (! empty($request->input('mail1')) &&
                $this->regist_user_service->checkExistEmail($request->input('mail1'))) {
                $validator->errors()->add('mail1', ErrorCodeConst::ERR_CODE_13);
            }
        });

        return $validator;
    }
}
