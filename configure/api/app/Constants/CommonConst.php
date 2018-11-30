<?php

namespace App\Constants;

class CommonConst
{

    // アプリ系のアクションの前頭
    const APP = 'app_';

    // 登録アクション
    const APP_REGIST = 'regist';

    // 更新アクション
    const APP_CHANGE = 'change';

    // 削除アクション
    const APP_DELETE = 'delete';

    // ログインアクション
    const APP_LOGIN = 'login';

    // マイページ画面にアクセス処理
    const APP_MYPAGE = 'mypage';

    // 退会アクション
    const APP_UNSUBSCRIBE = 'unsubscribe';

    // 使用の状況
    const APP_CHECK = 'check';

    // ユーザ新規登録情報の確認
    const APP_APPROVAL = 'approval';

    // パスワード更新アクション
    const APP_SEND_PASS = 'send_pass';

    // データ入力アクション
    const PG_INPUT = 'input';

    // データ入力確認アクション
    const PG_CONF = 'conf';

    // 処理完了アクション
    const PG_COMP = 'comp';

    // ルートディレクトリ
    const ROOT_DIR = '/home/www/ssl/';

    // nasディレクトリ
    // ======本番では rw が rw4となるので注意=======
    const NAS_DIR = '/home/www/rw/api/';

    // configディレクトリ
    const CONFIG_DIR = self::ROOT_DIR . 'configure/api/';

    // xmlディレクトリ
    const XML_DIR = self::NAS_DIR . 'xml/';

    // アクセスキー書き出しディレクトリ
    const ACCESS_KEY_DIR = self::NAS_DIR . 'accesskey/';

    // アクセスキーファイル内容記述データ
    const LIMIT_SERVICEIN = '7200';

    // アプリの時間
    const LIMIT_OTHER = '1000';

    // サービス制限
    const RESTRICTUNIT_SERVICEIN = '+1 hour';

    // 他の制限
    const RESTRICTUNIT_OTHER = '+1 day';

    // サービス状態
    const STATUS_SERVICEIN = '4';

    // API利用状況確認画面の有効期間
    const LIMIT_API_CONF_URL = '+7 day';

    // 有効期限の計算
    const LIMIT_EXPIRE_DATE = '+90 day';

    // パスワードハッシュ追加文字数
    const PASS_ADD_NUM = 5;

    // パスワードASCII値シフト数
    const PASS_ASCII_SHIFT_NUM = 16;

    // apiユーザークッキー名
    const API_USER_COOKIE = 'api_user_id';

    // apiトークンクッキー名
    const API_TOKEN_COOKIE = 'GnaviApiToken';

    // セッション管理用Cookie
    const WEB_API_COOKIE = 'GnaviWebApi';

    // アクセスキー
    const ACCESS_KEY = 'poweredbygnavi';

    // プライベートキー
    const PRIVATE_KEY = 'private';

    // リンクの有効期限（現在1日）
    const EXPIRATION_DATE = 1;

    // 500文字制限
    const VARCHAR_500 = 500;

    // 256文字制限
    const VARCHAR_256 = 256;

    // 200文字制限
    const VARCHAR_200 = 200;

    // 130文字制限
    const VARCHAR_130 = 130;

    // 50文字制限
    const VARCHAR_50 = 50;

    // 10文字制限
    const VARCHAR_10 = 10;

    // 0文字チェック
    const NO_DATA = 0;

    // API_PRODUCTS.UP_URL_STATUS
    // 有効
    const UP_URL_STATUS_0 = 0;
    // 無効
    const UP_URL_STATUS_1 = 1;

    // チェックする文字タイプ(組み合わせ指定可)
    // 数字
    const CHECKTEXTINC_CHR_TYPE_NUMBER = 1;

    // アルファベット
    const CHECKTEXTINC_CHR_TYPE_ALPHABET = 2;

    // (デフォルト)
    const CHECKTEXTINC_CHR_TYPE_DEFAULT = (self::CHECKTEXTINC_CHR_TYPE_NUMBER | self::CHECKTEXTINC_CHR_TYPE_ALPHABET);

    // 等増分文字列チェックの増分指定
    // 増分0(連続文字列のみ 例："1111")
    const CHECKTEXTINC_INCREASE_MODE_ZERO = 0;

    // 増分1以内(例："1111" "1234" "9876")
    const CHECKTEXTINC_INCREASE_MODE_ONE = 1;

    // 増分なんでも(例："1111" "1234" "1357" "963")
    const CHECKTEXTINC_INCREASE_MODE_ALL = - 1;

    // Cookie情報
    const COOKIE_KEY5 = 'ぐるなびえーぴーあいーきっくとっせ';

    // 文字列定義:パスワード
    const PASSWORD = 'パスワード';

    // 文字列定義:PCメールアドレス
    const PC_MAIL_ADDRESS = 'PCメールアドレス';

    // 文字列定義:改行タグ
    const BR = '<br>';

    // メール送信時に使用するアプリケーションのデフォルト名称
    const DEFAULT_CONTENTS_NAME = '登録なし';

    // sha1ハッシュ化後の文字列の長さ
    const LENGTH_SHA1 = 40;

    // 文字列定義:お名前（漢字）/姓ラベル
    const LABEL_USERNAME1 = '姓:';

    // 文字列定義:お名前（漢字）/名ラベル
    const LABEL_USERNAME2 = '名:';

    // 文字列定義:郵便番号1ラベル
    const LABEL_ZIP_K1 = '郵便番号1：';

    // 文字列定義:郵便番号2ラベル
    const LABEL_ZIP_K2 = '郵便番号2：';

    // 文字列定義:会社郵便番号1ラベル
    const LABEL_ZIP_H1 = '会社郵便番号1：';

    // 文字列定義:会社郵便番号2ラベル
    const LABEL_ZIP_H2 = '会社郵便番号2：';

    // 文字列定義:会社電話番号1ラベル
    const LABEL_TEL_H1 = '電話番号1：';

    // 文字列定義:会社電話番号2ラベル
    const LABEL_TEL_H2 = '電話番号2：';

    // 文字列定義:会社電話番号3ラベル
    const LABEL_TEL_H3 = '電話番号3：';

    // メール送信時に使用するアプリケーションのデフォルト名称
    const LABEL_USER_ID = 'ユーザーID：';

    const LABEL_EMAIL = 'PCメールアドレス：';

    // メールラベル
    const LABEL_EMAIL_LOGIN = 'PCメールアドレス';

    // パスワードラベル
    const LABEL_PASS_LOGIN = 'パスワード';

    // CHANGE_LOGテーブルのラベルが変更される。
    const LABEL_CHANGE = '変更';

    // フォルダ操作権限
    const MODE_CREATE_FOLDER = 0777;

    // ファイル操作権限
    const MODE_OPEND_FILE = 'w';

    // 日付形式
    const FORMAT_DATE = 'YmdHis';

    // DateTimeの形式
    const DATE_FORMAT_FULL = 'Y-m-d H:i:s';
}
