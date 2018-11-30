<?php
namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Utils\CookieChecker;
use Illuminate\Http\Request;
use App\Constants\ErrorCodeConst;
use App\Constants\CommonConst;
use Illuminate\Support\Facades\Log;
use App\Http\Services\ChangeUserService;

class ChangeUserController extends Controller
{

    /**
     * サービス
     *
     * @var ChangeUserService $change_user_service
     */
    private $change_user_service;

    public function __construct()
    {
        $this->change_user_service = app(ChangeUserService::class);
    }

    /**
     * ユーザ情報更新処理
     *
     * @param Request $request
     * @throws \Exception
     */
    public function doChange(Request $request)
    {
        $ary_post_data = $request->all();

        try {
            CookieChecker::sessionStart();
            $pg = $request->input('p');

            if ($pg == CommonConst::PG_INPUT) {
                $data = $this->change_user_service->changeInput();

                return view('change_input')->with($data);
            } elseif ($pg == CommonConst::PG_CONF) {
                $this->validateChangeUser($request);
                $data = $this->change_user_service->changeConf();

                return view('change_conf')->with($data);
            } elseif ($pg == CommonConst::PG_COMP) {
                $this->validateChangeUser($request);
                $this->change_user_service->changeComp();

                return view('change_comp');
            } else {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
            }
        } catch (ApiException $e) {
            return view('change_input')->with('user_data', $ary_post_data)
                ->with('prefList', $this->change_user_service->getPref())
                ->withErrors($this->change_user_service->getDataError());
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_04,
                'msg' => $ex->getMessage()
            ], []);
        }

        return true;
    }

    /**
     * ユーザー情報をバリデーションチェックする。
     *
     * @param
     *            $request
     * @throws \Exception
     * @throws ApiException
     */
    private function validateChangeUser($request)
    {
        $validator = $this->doValidate($request, $this->rulesUser($request));
        if ($validator->fails()) {
            $ary_post_data = $request->all();

            if ($ary_post_data['user_type'] == 1) {
                $result = $this->change_user_service->getListPref();

                if (! $result) {
                    throw new \Exception(ErrorCodeConst::ERR_CODE_09);
                } else {
                    $this->change_user_service->setPref($result);
                }
            }

            $this->change_user_service->setDataError($validator);

            throw new ApiException(ErrorCodeConst::ERR_CHECK_DATA);
        }
    }
}
