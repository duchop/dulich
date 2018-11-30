<?php
namespace App\Http\Controllers;

use App\Utils\CookieChecker;
use Illuminate\Http\Request;
use App\Constants\ErrorCodeConst;
use App\Http\Services\MypageService;
use Illuminate\Support\Facades\Log;

class MypageController extends Controller
{

    /**
     * サービス
     *
     * @var MypageService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(MypageService::class);
    }

    /**
     * マイページ画面にアクセス処理
     *
     * @param Request $request
     */
    public function createMyPage(Request $request)
    {
        try {
            CookieChecker::sessionStart();

            $api_user_id = session()->get('api_user_id');

            // ユーザーが「アプリ遅延」ボタンを押した場合
            if (! is_null($request->input('extension'))) {
                if (strcmp($request->input('extension'), "extension") == 0) {
                    $this->service->extendExpiration();

                    return redirect('mypage');
                }
            }

            $data = $this->service->getData($api_user_id);

            return view('/mypage')->with($data);
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'title' => ErrorCodeConst::ERR_TITLE_06,
                'msg' => $ex->getMessage()
            ], []);
        }
    }
}
