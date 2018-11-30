<?php
/**
 * custom validateクラス
 */
namespace App\Validation;

use Illuminate\Validation\Validator;
use App\Constants\RegularConst;
use App\Constants\CommonConst;
use App\Utils\Util;

class CustomValidator extends Validator
{

    /**
     * CustomValidatorクラスのコンストラクタ
     *
     * @param Translator $translator
     * @param array $data
     * @param array $rules
     * @param array $messages
     */
    public function __construct($translator, $data = [], $rules = [], $messages = [])
    {
        parent::__construct($translator, $data, $rules, $messages);
    }

    /**
     * 電子メールの検証を上書きする
     *
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    public function validateEmail($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_MAIL, $value)) {
            return false;
        }

        return true;
    }

    /**
     * パスワード入力チェック
     *
     * @param string $attribute
     * @param string $value
     *            パスワード
     * @return boolean
     */
    public function validatePassword($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_PASSWORD, $value)) {
            return false;
        }

        return true;
    }

    /**
     * パスワード強度チェックルール
     *
     * @param string $attribute
     * @param string $value
     *            パスワード
     * @return boolean
     */
    public function validateTextIncrease($attribute, $value)
    {
        /**
         *
         * @var $util Util
         */
        $util = app(Util::class);
        if (! $util->checkTextIncrease($value)) {
            return false;
        }
        return true;
    }

    /**
     * アプリ種類チェックルール
     *
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    public function validateServiceStatus($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_SERVICE_STATUS, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 禁則文字入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            文字列
     * @return boolean
     */
    public function validateForbiddenChar($attribute, $value)
    {
        if (preg_match(RegularConst::CHECK_FORBIDDEN_CHAR, $value)) {
            return false;
        }

        return true;
    }

    /**
     * ユーザーID入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            ユーザーID
     * @return boolean
     */
    public function validateUserId($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_USER_ID, $value)) {
            return false;
        }

        return true;
    }

    /**
     * アクセスキーチェック
     *
     * @param string $attribute
     * @param
     *            String value アクセスキー
     * @return boolean
     */
    public function validateAccessKey($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_ACCESS_KEY, value)) {
            return false;
        }

        return true;
    }

    /**
     * 氏名:漢字入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            氏名:漢字
     * @return boolean
     */
    public function validateZenkaku($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_USER_NAME, $value) &&
            ! preg_match(RegularConst::CHECK_USER_NAME_EN, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 個人・法人区分チェック
     *
     * @param string $attribute
     * @param String $value
     *
     * @return boolean
     */
    public function validateUserType($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_SERVICE_IN, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 氏名:ふりがな入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            氏名:ふりがな
     * @return boolean
     */
    public function validateUserNameKana($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_USER_NAME_KANA, $value)) {
            return false;
        }

        return true;
    }

    /**
     * コンテンツ名チェック
     *
     * @param string $attribute
     * @param String $url
     *            URL
     * @return boolean
     */
    public function validateContentsName($attribute, $value)
    {
        if (mb_strwidth($value, 'UTF-8') / 2 > CommonConst::VARCHAR_130) {
            return false;
        }

        return true;
    }

    /**
     * アプリの概要の文字列の長さチェック
     *
     * @param string $attribute
     * @param String $value
     *            概要
     * @return boolean
     */
    public function validateContentsDescription($attribute, $value)
    {
        if (mb_strwidth($value, 'UTF-8') / 2 > CommonConst::VARCHAR_500) {
            return false;
        }

        return true;
    }

    /**
     * アプリのサービスの種類が正常かチェック
     *
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    public function validateServiceIn($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_SERVICE_IN, $value)) {
            return false;
        }

        return true;
    }

    /**
     * アプリ種類が正常かチェック
     *
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    public function validateContentsType($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_CONTENTS_TYPE, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 掲載予定URL入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            URL
     * @return boolean
     */
    public function validateUrlSize($attribute, $value)
    {
        if (mb_strlen($value) > CommonConst::VARCHAR_200) {
            return false;
        }

        return true;
    }

    /**
     * 掲載予定URL入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            URL
     * @return boolean
     */
    public function validateUrlFormat($attribute, $value)
    {
        if (mb_strlen($value) != CommonConst::NO_DATA && ! preg_match(RegularConst::CHECK_URL, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 電話番号入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            電話番号
     * @return boolean
     */
    public function validateTel($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_TEL, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 郵便番号1入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            郵便番号1
     * @return boolean
     */
    public function validateZip1($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_ZIP1, $value)) {
            return false;
        }

        return true;
    }

    /**
     *
     * 郵便番号2入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            郵便番号2
     * @return boolean
     */
    public function validateZip2($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_ZIP2, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 都道府県入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            都道府県コード
     * @return boolean
     */
    public function validatePref($attribute, $value)
    {
        if (! $prefCheck = preg_match(RegularConst::CHECK_PREF, $value)) {
            return false;
        }

        return true;
    }

    /**
     * 市区町村名入力チェック
     *
     * @param string $attribute
     * @param String $value
     *            市区町村名
     * @return boolean
     */
    public function validateCity($attribute, $value)
    {
        if (! preg_match(RegularConst::CHECK_CITY, $value)) {
            return false;
        }

        return true;
    }

    /**
     * マッピングチェック
     *
     * @param string $attribute
     * @param String $value
     *
     * @return boolean
     */
    public function validateMappingChar($attribute, $value)
    {
        /**
         *
         * @var $util Util
         */
        $util = app(Util::class);
        if ($ret = $util->mappingCharCheck($value) !== true) {
            return false;
        }

        return true;
    }

    /**
     * スペース文字がある文字列のバリデーションをチェックする。
     *
     * @param string $attribute
     * @param String $value
     *
     * @return boolean
     */
    public function validateSpaceChar($attribute, $value)
    {
        if (preg_match(RegularConst::CHECK_SPACE, $value)) {
            return false;
        }

        return true;
    }
}
