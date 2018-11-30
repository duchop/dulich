<?php
/**
 * レギュラー
 *
 * @author Luvina
 */
namespace App\Constants;

class RegularConst
{

    /**
     * パラメータトリム(* 　、 、\t、\n、\r)にマッチ
     */
    const CHECK_PARAM_TRIM = '/^(　| |\t|\n|\r)*|(　| |\t|\n|\r)*$/';

    /**
     * 禁則文字入力チェック
     * <、>、'、"、;にマッチ
     */
    const CHECK_FORBIDDEN_CHAR = '/[|<>;:"]/';

    /**
     * 半角カタカナ入力チェック
     * 半角カタカナにマッチ
     */
    const CHECK_HANKAKU_KANA = '[｡-ﾟ]';

    /**
     * ユーザーID入力チェック
     * 英数字6～50にマッチ
     */
    const CHECK_USER_ID = '/^[0-9A-Za-z]{6,50}$/';

    /**
     * アクセスキーチェック
     * 英数字32文字にマッチ
     */
    const CHECK_ACCESS_KEY = '/^[0-9a-z]{32}$/';

    /**
     * パスワード入力チェック
     * 英数字6～50にマッチ
     */
    const CHECK_PASSWORD = '/^[0-9A-Za-z]{6,16}$/';

    /**
     * 氏名:漢字入力チェック
     * 漢字、ひらがな、全角カナにマッチ
     */
    const CHECK_USER_NAME = '/^[一-龠々〃ぁ-んゝゞァ-ヶヽヾー]{1,10}$/u';

    const CHECK_USER_NAME_EN = '/^[a-zA-Z\s]{1,20}$/';

    /**
     * ふりがな入力チェック
     * ひらがなにマッチ
     */
    const CHECK_USER_NAME_KANA = '/^[ぁ-んーゞゝ]{1,10}$/u';

    /**
     * メールアドレス入力チェック
     * 最短1@1.jp
     * 長いのはtext@text.text.text.text.info等
     */
    const CHECK_MAIL = '/^[0-9A-Za-z][0-9A-Za-z_\.\-\+]*@[0-9A-Za-z][0-9A-Za-z_\.\-]*\.[A-Za-z]{2,4}$/';

    /**
     * URL入力チェック
     */
    const CHECK_URL = '/^https?:\/\/[0-9A-Za-z][0-9A-Za-z%&#=_+.?~\-\/]*$/';

    /**
     * 電話番号入力チェック
     * 半角数字1～5文字にマッチ
     */
    const CHECK_TEL = '/^[0-9]{1,5}$/';

    /**
     * 郵便番号1入力チェック
     * 半角数字3文字にマッチ
     */
    const CHECK_ZIP1 = '/^[0-9]{3}$/';

    /**
     * 郵便番号2入力チェック
     * 半角数字4文字にマッチ
     */
    const CHECK_ZIP2 = '/^[0-9]{4}$/';

    /**
     * 都道府県入力チェック
     * PREF半角数字2文字にマッチ
     */
    const CHECK_PREF = '/^PREF(0[1-9]|[1-3][0-9]|4[0-7])$/';

    /**
     * 市区町村名入力チェック
     * 漢字、ひらがな、全角カナ、数字にマッチ
     */
    const CHECK_CITY = '/^[0-9０-９一-龠々〃ぁ-んゝゞァ-ヶヽヾー]{1,50}$/u';

    /**
     * ユーザIDチェックパターン
     */
    const CHECK_API_USER_ID = '/([0-9]*):([a-zA-Z0-9]*)/';

    /**
     * アプリ種類チェックパターン
     */
    const CHECK_SERVICE_STATUS = '/[123]/';

    /**
     * サービス種類チェックパターン
     */
    const CHECK_SERVICE_IN = '/[01]/';

    /**
     * アプリ種類チェックパターン
     */
    const CHECK_CONTENTS_TYPE = '/[12]/';

    /**
     * スペース文字がある時の確認パターン
     */
    const CHECK_SPACE = '/\s/';
}
