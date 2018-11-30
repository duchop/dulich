<?php
namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Services\RegisterAppService;
use Illuminate\Http\Request;
use App\Utils\CookieChecker;
use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use Illuminate\Support\Facades\Log;

class RegisterAppController extends Controller
{

    /**
     * サービス
     *
     * @var RegisterAppService $regist_app_service
     */
    private $regist_app_service;

    public function __construct()
    {
        $this->regist_app_service = app(RegisterAppService::class);
    }

    /**
     * アプリ新規登録の制御
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doAppRegist(Request $request)
    {
        try {
            CookieChecker::sessionStart();
            $p = $request->input('p');

            if ($p == CommonConst::PG_INPUT) {
                return $this->doAppRegistInput($request);
            } elseif ($p == CommonConst::PG_CONF) {
                return $this->doAppRegistConf($request);
            } elseif ($p == CommonConst::PG_COMP) {
                return $this->doAppRegistComp($request);
            } else {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
            }
        } catch (ApiException $e) {
            return view('app_regist_input', [
                'postData' => $request,
                'msg' => $e->getMessage()
            ]);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_03,
                'msg' => $ex->getMessage()
            ]);
        }
    }

    /**
     * アプリ新規登録用入力画面の制御
     *
     * @param
     *            $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    private function doAppRegistInput($request)
    {
        try {
            $this->regist_app_service->appRegistInput();

            return view('app_regist_input', [
                'error' => null,
                'postData' => $request
            ]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * アプリ新規登録用確認画面の制御
     *
     * @param
     *            $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    private function doAppRegistConf($request)
    {
        try {
            $validator = $this->validateRegistNewApp($request);

            // バリデーションチェックを行う。
            if ($validator->fails()) {
                return view('app_regist_input', [
                    'postData' => $request
                ])->withErrors($validator);
            }

            $api_user_id = session()->get('api_user_id');

            // アプリケーションの登録制限数と試用制限数を確認する。
            $this->regist_app_service->checkLimitApp($api_user_id);

            return view('app_regist_conf', [
                'postData' => $request
            ]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * アプリケーション新規登録完了画面の制御
     *
     * @param
     *            $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    private function doAppRegistComp($request)
    {
        try {
            $validator = $this->validateRegistNewApp($request);

            // バリデーションチェックを行う。
            if ($validator->fails()) {
                return view('app_regist_input', [
                    'postData' => $request
                ])->withErrors($validator);
            }

            $api_user_id = session()->get('api_user_id');

            // アプリケーションの登録制限数と試用制限数を確認する。
            $this->regist_app_service->checkLimitApp($api_user_id);

            $this->regist_app_service->appRegistComp();

            return view('app_regist_comp');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * アプリケーション登録用データをバリデーションチェックする。
     *
     * @param
     *            $request
     * @return mixed
     */
    private function validateRegistNewApp($request)
    {
        $validator = $this->doValidate($request, $this->rulesApplication($request));

        $validator->after(function ($validator) use ($request) {
            if ($request->input('service_status') == 1 && $this->regist_app_service->countTrialApp() == 1) {
                $validator->errors()
                    ->add('service_status', ErrorCodeConst::ERR_CODE_21);
                $request->trial = 1;
            }

            if ($request->input('service_status') == 3 && ! empty($request->input('url')) &&
                $this->regist_app_service->checkExistUrl($request->input('url'))) {
                $validator->errors()->add('url', ErrorCodeConst::ERR_CODE_13);
            }
        });

        return $validator;
    }
}
