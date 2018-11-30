<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Constants\ErrorCodeConst;
use App\Constants\CommonConst;
use App\Utils\Util;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * $rules に記述されたバリデーションルールに従い、バリデーションを行う
     *
     * @param Request $request
     * @param array $aryRule
     */
    public function doValidate($request, $ary_rule)
    {
        $ary_validation_rule = [];
        $ary_error_message = [];

        foreach ($ary_rule as $form_name => $ary_definition) {
            if (is_array($ary_definition) && count($ary_definition) > 0) {
                foreach ($ary_definition as $validation_key => $validation_value) {
                    $ary_validation_rule[$form_name][] = $validation_key ?? '';
                    $ary_validation_key = explode(':', $validation_key);
                    $ary_error_message["{$form_name}.{$ary_validation_key[0]}"] = $validation_value ?? '';
                }
            }
        }
        // バリデーションはフレームワークに任せる
        // バリデートエラー時はフレームワークが自動で自分自身にリダイレクトする（値やテンプレート変数も保持してくれる）
        return \Validator::make($request->all(), $ary_validation_rule ?? [], $ary_error_message ?? []);
    }

    /**
     * ユーザの情報とアプリの情報のチェックルール
     *
     * @return array
     */
    protected function rulesRegist(Request $request)
    {
        $ary_rules = $this->rulesUser($request);
        $ary_rules = array_merge($ary_rules, $this->rulesApplication($request));
        return $ary_rules;
    }

    /**
     * ユーザの情報のチェックルール
     *
     * @return array
     */
    protected function rulesUser(Request $request)
    {
        /**
         *
         * @var $util Util
         */
        $util = app(Util::class);
        $ary_rules = [
            'user_id' => [
                'required' => ErrorCodeConst::ERR_CODE_05,
                'user_id' => ErrorCodeConst::ERR_CODE_12
            ],
            'pass1' => [
                'required' => ErrorCodeConst::ERR_CODE_05,
                'password' => ErrorCodeConst::ERR_CODE_36,
                'text_increase' => ErrorCodeConst::ERR_CODE_13,
                'same:pass2' => CommonConst::PASSWORD . ErrorCodeConst::ERR_CODE_06
            ],
            'pass2' => [
                'required' => ErrorCodeConst::ERR_CODE_05,
                'password' => ErrorCodeConst::ERR_CODE_36,
                'text_increase' => ErrorCodeConst::ERR_CODE_13
            ],
            'user_name1' => [
                'required' => CommonConst::LABEL_USERNAME1 . ErrorCodeConst::ERR_CODE_05,
                'zenkaku' => CommonConst::LABEL_USERNAME1 . ErrorCodeConst::ERR_CODE_26,
                'mapping_char' => CommonConst::LABEL_USERNAME1 . ErrorCodeConst::ERR_CODE_24 .
                    $util->mappingCharCheck($request->input('user_name1')) . CommonConst::BR,
                'space_char' => CommonConst::LABEL_USERNAME1 . ErrorCodeConst::ERR_CODE_48
            ],
            'user_name2' => [
                'required' => CommonConst::LABEL_USERNAME2 . ErrorCodeConst::ERR_CODE_05,
                'zenkaku' => CommonConst::LABEL_USERNAME2 . ErrorCodeConst::ERR_CODE_26,
                'mapping_char' => CommonConst::LABEL_USERNAME2 . ErrorCodeConst::ERR_CODE_24 .
                    $util->mappingCharCheck($request->input('user_name2')) . CommonConst::BR,
                'space_char' => CommonConst::LABEL_USERNAME2 . ErrorCodeConst::ERR_CODE_48
            ],
            'mail1' => [
                'required' => ErrorCodeConst::ERR_CODE_05,
                'email' => ErrorCodeConst::ERR_CODE_07,
                'max:256' => CommonConst::VARCHAR_256 . ErrorCodeConst::ERR_CODE_23,
                'same:mail2' => CommonConst::PC_MAIL_ADDRESS . ErrorCodeConst::ERR_CODE_06
            ],
            'mail2' => [
                'required' => ErrorCodeConst::ERR_CODE_05,
                'email' => ErrorCodeConst::ERR_CODE_07,
                'max:256' => CommonConst::VARCHAR_256 . ErrorCodeConst::ERR_CODE_23
            ],
            'user_type' => [
                'required' => ErrorCodeConst::ERR_CODE_21,
                'user_type' => ErrorCodeConst::ERR_CODE_21
            ]
        ];

        if ($request->input('foreign_status_k') == 0 && $request->input('user_type') == 0) {
            $ary_rules = array_merge($ary_rules, [
                'zip_k_1' => [
                    'required' => CommonConst::LABEL_ZIP_K1 . ErrorCodeConst::ERR_CODE_05,
                    'zip1' => CommonConst::LABEL_ZIP_K1 . ErrorCodeConst::ERR_CODE_19
                ],

                'zip_k_2' => [
                    'required' => CommonConst::LABEL_ZIP_K2 . ErrorCodeConst::ERR_CODE_05,
                    'zip2' => CommonConst::LABEL_ZIP_K2 . ErrorCodeConst::ERR_CODE_20
                ]
            ]);
        }

        if ($request->input('user_type') == 1) {
            $ary_rules = array_merge($ary_rules, [
                'corporation_name' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'max:50' => CommonConst::VARCHAR_50 . ErrorCodeConst::ERR_CODE_23,
                    'forbidden_char' => ErrorCodeConst::ERR_CODE_22
                ],
                'department' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'max:50' => CommonConst::VARCHAR_50 . ErrorCodeConst::ERR_CODE_23,
                    'forbidden_char' => ErrorCodeConst::ERR_CODE_22
                ]
            ]);
        }

        if ($request->input('foreign_status_h') == 0 && $request->input('user_type') == 1) {
            $ary_rules = array_merge($ary_rules, [
                'zip_h_1' => [
                    'required' => CommonConst::LABEL_ZIP_H1 . ErrorCodeConst::ERR_CODE_05,
                    'zip1' => CommonConst::LABEL_ZIP_H1 . ErrorCodeConst::ERR_CODE_20
                ],
                'zip_h_2' => [
                    'required' => CommonConst::LABEL_ZIP_H2 . ErrorCodeConst::ERR_CODE_05,
                    'zip2' => CommonConst::LABEL_ZIP_H2 . ErrorCodeConst::ERR_CODE_20
                ],
                'pref_h' => [
                    'required' => ErrorCodeConst::ERR_CODE_25,
                    'pref' => ErrorCodeConst::ERR_CODE_21
                ],
                'city_h' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'city' => ErrorCodeConst::ERR_CODE_29,
                    'mapping_char' => ErrorCodeConst::ERR_CODE_24 .
                        $util->mappingCharCheck($request->input('city_h')) . CommonConst::BR
                ],
                'street_h' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'max:50' => CommonConst::VARCHAR_50 . ErrorCodeConst::ERR_CODE_23,
                    'forbidden_char' => ErrorCodeConst::ERR_CODE_22
                ],
                'tel_h_1' => [
                    'required' => CommonConst::LABEL_TEL_H1 . ErrorCodeConst::ERR_CODE_05,
                    'tel' => CommonConst::LABEL_TEL_H1 . ErrorCodeConst::ERR_CODE_18
                ],
                'tel_h_2' => [
                    'required' => CommonConst::LABEL_TEL_H2 . ErrorCodeConst::ERR_CODE_05,
                    'tel' => CommonConst::LABEL_TEL_H2 . ErrorCodeConst::ERR_CODE_18
                ],
                'tel_h_3' => [
                    'required' => CommonConst::LABEL_TEL_H3 . ErrorCodeConst::ERR_CODE_05,
                    'tel' => CommonConst::LABEL_TEL_H3 . ErrorCodeConst::ERR_CODE_18
                ]
            ]);
        }

        return $ary_rules;
    }

    /**
     * アプリの情報のチェックルール
     *
     * @return array
     */
    protected function rulesApplication($request)
    {
        $ary_rules = [
            'service_status' => [
                'required' => ErrorCodeConst::ERR_CODE_21,
                'service_status' => ErrorCodeConst::ERR_CODE_25
            ]
        ];

        if ($request->input('service_status') == 3) {
            $ary_rules = array_merge($ary_rules, [
                'service_in' => [
                    'required' => ErrorCodeConst::ERR_CODE_25,
                    'service_in' => ErrorCodeConst::ERR_CODE_21
                ]
            ]);
        }

        if ($request->input('service_status') == 3 && $request->input('service_in') == 1) {
            $ary_rules = array_merge($ary_rules, [
                'contents_name' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'contents_name' => CommonConst::VARCHAR_130 . ErrorCodeConst::ERR_CODE_23
                ],
                'contents_type' => [
                    'required' => ErrorCodeConst::ERR_CODE_25,
                    'contents_type' => ErrorCodeConst::ERR_CODE_21
                ],
                'url' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'url_format' => ErrorCodeConst::ERR_CODE_07,
                    'url_size' => CommonConst::VARCHAR_200 . ErrorCodeConst::ERR_CODE_23
                ]
            ]);
        }

        if ($request->input('service_status') == 2) {
            $ary_rules = array_merge($ary_rules, [
                'contents_description' => [
                    'required' => ErrorCodeConst::ERR_CODE_05,
                    'contents_description' => CommonConst::VARCHAR_500 . ErrorCodeConst::ERR_CODE_19
                ]
            ]);
        }
        return $ary_rules;
    }
}
