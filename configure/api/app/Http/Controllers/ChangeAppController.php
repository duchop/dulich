<?php
namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Utils\CookieChecker;
use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use Illuminate\Support\Facades\Log;
use App\Http\Services\ChangeAppService;

class ChangeAppController extends Controller
{

    /**
     *
     * @var ChangeAppService $change_app_service
     */
    private $change_app_service;

    public function __construct()
    {
        $this->change_app_service = app(ChangeAppService::class);
    }

    /**
     * アプリ情報更新アクション
     *
     * @param Request $request
     * @throws \Exception
     */
    public function doAppChange(Request $request)
    {
        $ary_post_data = $request->all();

        try {
            CookieChecker::sessionStart();
            $pg = $request->input('p');

            if ($pg == CommonConst::PG_INPUT) {
                $ary_post_data = $this->change_app_service->appChangeInput();

                return view('app_change_input')->with('post_data', $ary_post_data);
            } elseif ($pg == CommonConst::PG_CONF) {
                $validator = $this->validateChangeApp($request);
                if ($validator->fails()) {
                    return view('app_change_input')->with('post_data', $ary_post_data)->withErrors($validator);
                }
                $this->change_app_service->appChangeConf();

                return view('app_change_conf')->with('post_data', $ary_post_data);
            } elseif ($pg == CommonConst::PG_COMP) {
                $validator = $this->validateChangeApp($request);
                if ($validator->fails()) {
                    return view('app_change_input', [
                        'post_data' => $ary_post_data
                    ])->withErrors($validator);
                }
                $this->change_app_service->appChangeComp();

                return view('app_change_comp');
            } else {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
            }
        } catch (ApiException $e) {
            return view('app_change_input')->with('post_data', $ary_post_data)
                                           ->withErrors($this->change_app_service->getDataError());
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_01,
                'msg' => $ex->getMessage()
            ], []);
        }
        return;
    }

    /**
     * パラメータバリデーション
     *
     * @param
     *            $request
     * @throws ApiException
     */
    private function validateChangeApp($request)
    {
        $validator = $this->doValidate($request, $this->rulesApplication($request));

        $validator->after(function ($validator) use ($request) {

            if ($request->input('service_status') == 3 && ! empty($request->input('url')) &&
                $this->change_app_service->checkExistUrl($request->input('url'), $request->input('access_key'))) {
                $validator->errors()->add('url', ErrorCodeConst::ERR_CODE_13);
            }
        });

        return $validator;
    }
}
