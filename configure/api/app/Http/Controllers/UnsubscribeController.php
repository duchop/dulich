<?php
namespace App\Http\Controllers;

use App\Constants\CommonConst;
use App\Constants\ErrorCodeConst;
use App\Exceptions\ApiException;
use App\Http\Services\UnsubscribeService;
use App\Utils\CookieChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UnsubscribeController extends Controller
{

    /**
     * サービス
     *
     * @var UnsubscribeService $unsubscribe_service
     */
    private $unsubscribe_service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->unsubscribe_service = app(UnsubscribeService::class);
    }

    /**
     * 退会画面を制御する。
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doUnsub(Request $request)
    {
        try {
            CookieChecker::sessionStart();

            $p = $request->input('p');

            // メソッドと pパラメータを確認する。
            if ($p == CommonConst::PG_INPUT) {
                return view('unsubscribe', [
                    'postData' => $request
                ]);
            } elseif ($p == CommonConst::PG_COMP) {
                return $this->unsubscribeComp($request);
            } else {
                throw new \Exception(ErrorCodeConst::ERR_CODE_16);
            }
        } catch (ApiException $e) {
            return view('unsubscribe', [
                'postData' => $request
            ])->withErrors($e->getMessage());
        } catch (\Exception $ex) {
            Log::error($ex->getFile() . ' : ' . $ex->getLine() . ' : ' . $ex->getMessage());

            return view('error', [
                'msg' => $ex->getMessage(),
                'title' => ErrorCodeConst::ERR_TITLE_09
            ]);
        }
    }

    /**
     * 退会完了画面の制御
     *
     * @param
     *            $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    private function unsubscribeComp($request)
    {
        try {
            $post_data = $request;

            // データのバリデーションをチェックする
            $validator = $this->doValidate($request, $this->rules());

            $validator->after(function ($validator) use ($post_data) {
                if (isset($post_data->reason09) && $post_data->reason09 == 1) {
                    if (! isset($post_data->more_reason)) {
                        $validator->errors()
                            ->add('more_reason', ErrorCodeConst::ERR_CODE_38);
                    } elseif (isset($post_data->more_reason) && mb_strlen($post_data->more_reason) == 0) {
                        $validator->errors()
                            ->add('more_reason', ErrorCodeConst::ERR_CODE_38);
                    } elseif (isset($post_data->more_reason) &&
                        (mb_strwidth($post_data->more_reason, 'UTF-8') / 2 > CommonConst::VARCHAR_50)) {
                        $validator->errors()
                            ->add('more_reason', ErrorCodeConst::ERR_CODE_38);
                    }
                }

                if (isset($post_data->more_reason) && mb_strlen($post_data->more_reason) > 0 &&
                    ! isset($post_data->reason09)) {
                    $validator->errors()
                        ->add('more_reason', ErrorCodeConst::ERR_CODE_39);
                }
            });

            // バリデーションエラーがある。
            if ($validator->fails()) {
                return view('unsubscribe', [
                    'postData' => $post_data
                ])->withErrors($validator);
            }

            $this->unsubscribe_service->unsubscribeComp();

            return view('unsubscribe_comp');
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $ex) {
            throw $ex;
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
            'pass' => [
                'required' => ErrorCodeConst::ERR_CODE_40
            ]
        ];
    }
}
