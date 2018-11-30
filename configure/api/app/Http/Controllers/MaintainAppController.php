<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\CookieChecker;
use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use Illuminate\Support\Facades\Log;
use App\Http\Services\MaintainAppService;

class MaintainAppController extends Controller
{

    /**
     * サービス
     *
     * @var MaintainAppService $maintain_app_service
     */
    private $maintain_app_service;

    public function __construct()
    {
        $this->maintain_app_service = app(MaintainAppService::class);
    }

    /**
     * アプリケーションを削除する処理をナビゲーションする関数
     *
     * @param Request $request
     * @return view
     */
    public function doAppDelete(Request $request)
    {
        try {
            CookieChecker::sessionStart();

            // クッキーのapi_user_idとセッションのapi_user_idが同じことを確認する
            $ary_request = $request->all();
            $ary_request = array_add($ary_request, 'api_user_id', session()->get('api_user_id'));

            if ($ary_request['p'] === CommonConst::PG_CONF) {
                // アプリケーション削除の確認
                $result = $this->maintain_app_service->appDeleteConf();

                return view('app_delete_conf', [
                    'postData' => $result
                ], []);
            } elseif ($ary_request['p'] === CommonConst::PG_COMP) {
                // アプリケーションを削除する。
                $this->maintain_app_service->appDeleteComp();

                return view('app_delete_comp');
            } else {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
                return;
            }
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_02,
                'msg' => $ex->getMessage()
            ], []);
        }
    }

    /**
     * API使用状況を確認するアクション
     *
     * @param Request $request
     * @return view
     */
    public function doAppCheck(Request $request)
    {
        try {
            $ary_request = $request->all();
            $ary_path = explode("/", $request->getPathInfo());
            $ary_request['pass_query'] = $ary_path[2];

            if ($ary_request['p'] === CommonConst::PG_INPUT) {
                $result = $this->maintain_app_service->appCheckInput();

                return view('app_check_input', [
                    'postData' => $result
                ], []);
            } elseif ($ary_request['p'] === CommonConst::PG_COMP) {
                $this->maintain_app_service->appCheckComp();

                return view('app_check_comp');
            } else {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
                return;
            }
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_10,
                'msg' => $ex->getMessage()
            ], []);
        }
    }
}
