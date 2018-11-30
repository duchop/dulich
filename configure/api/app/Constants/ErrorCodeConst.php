<?php
/**
 * エラーメッセージ
 *
 * @author Luvina
 */
namespace App\Constants;

class ErrorCodeConst
{
    // DB_ERRORなど
    const ERR_CODE_01 = '登録に失敗しました。<br>リンクをコピーしてアドレスバーに直接貼り付けて再度アクセスしてください。<br>登録できない場合は、カスタマーサポートセンターまでご連絡ください。<br>';

    // 有効期限切れ
    const ERR_CODE_02 = '登録の有効期限が切れています。<br>再度登録してください。<br>';

    // 登録済み
    const ERR_CODE_03 = '既に登録されています。<br>';

    // URL不正
    const ERR_CODE_04 = 'URLが不正です。<br>';

    // 未入力
    const ERR_CODE_05 = '未入力です。<br>';

    // 不一致
    const ERR_CODE_06 = 'が一致していません。<br>';

    // 不正
    const ERR_CODE_07 = '入力値が不正です。<br>';

    // DB_ERRORなど
    const ERR_CODE_08 = '登録に失敗しました。<br>登録できない場合は、カスタマーサポートセンターまでご連絡ください。<br>';

    // DB_ERRORなど
    const ERR_CODE_09 = 'データ取得に失敗しました。<br>データ取得できない場合は、カスタマーサポートセンターまでご連絡ください。<br>';

    // ログイン失敗
    const ERR_CODE_10 = 'PCメールアドレス又はパスワードが間違っています。<br>';

    // PCメールアドレス登録なし
    const ERR_CODE_11 = 'ユーザーID又はPCメールアドレスが間違っています。<br>';

    // ユーザーID、パスワード入力エラー
    const ERR_CODE_12 = '半角英数字6文字以上50文字以下で入力してください。<br>';

    // 入力値ユニークエラー
    const ERR_CODE_13 = '既存の登録情報と入力値が同じですので、お手数ですが違う値を入力してください。<br>';

    // パスワード不一致
    const ERR_CODE_14 = 'パスワードが不一致です。<br>';

    // 変更箇所未入力
    const ERR_CODE_15 = '変更を入力してください。<br>';

    // 不正な遷移
    const ERR_CODE_16 = '不正な遷移です。<br>';

    // ふりがな入力エラー
    const ERR_CODE_17 = '全角ひらがなで10文字以下で入力してください。<br>';

    // 電話番号入力エラー
    const ERR_CODE_18 = '半角数字で5文字以下で入力してください。<br>';

    // 郵便番号1入力エラー
    const ERR_CODE_19 = '半角数字3桁で入力してください。<br>';

    // 郵便番号2入力エラー
    const ERR_CODE_20 = '半角数字4桁で入力してください。<br>';

    // パラメータ改ざん
    const ERR_CODE_21 = '不正な値です。<br>';

    // 禁則文字
    const ERR_CODE_22 = '不正な文字です。<br>';

    // 文字over
    const ERR_CODE_23 = '文字以内で入力してください。<br>';

    // 入力不可文字
    const ERR_CODE_24 = '以下の文字は入力できません。<br>';

    // 未選択
    const ERR_CODE_25 = '未選択です。<br>';

    // 全角入力
    const ERR_CODE_26 = '漢字、ひらがな、全角カタカナで10文字以下、あるいは、半角英字20文字以下で入力してください。<br>';

    // PCメールアドレス複数登録
    const ERR_CODE_27 = '入力されたPCメールアドレスが複数登録されてますので、<br>パスワードを送信することができません。<br>カスタマーサポートセンターまでご連絡ください。<br>';

    // レコード削除エラー
    const ERR_CODE_28 = '現在大変込み合っております。<br>再度時間を置いて登録してください。<br>';

    // 市区町村名入力
    const ERR_CODE_29 = '漢字、ひらがな、全角カタカナ、数字のいずれかで50文字以下で入力してください。<br>';

    // 未登録
    const ERR_CODE_30 = '登録されておりません。<br>再度登録してください。<br>';

    // リマインダー・利用停止状態
    const ERR_CODE_31 = '現在利用停止中のユーザーのため、パスワードを送信できません。<br>';

    // リマインダー・未承認
    const ERR_CODE_32 = 'ユーザー登録未完了のため、パスワードを送信できません。<br>';

    // ログイン・利用停止状態
    const ERR_CODE_33 = '現在利用停止中のユーザーのため、ログインできません。<br>';

    // ログイン・未承認
    const ERR_CODE_34 = 'ユーザー登録未完了のため、ログインできません。<br>';

    // パスワードの内容
    const ERR_CODE_35 = '推測されやすいパスワードです。パスワードを変更してください。<br>';

    // パスワードのフォーマット
    const ERR_CODE_36 = '半角英数字6文字以上16文字以下で入力してください。<br>';

    // DB_ERRORなど
    const ERR_CODE_37 = '退会処理に失敗しました。<br>カスタマーサポートセンターまでご連絡ください。<br>';

    // 退会理由のエラー
    const ERR_CODE_38 = '退会理由は50文字以下で入力してください。';

    // 退会理由のエラー
    const ERR_CODE_39 = '退会の理由にその他を選択してください。';

    // 退会　パスワードエラー
    const ERR_CODE_40 = 'パスワードを入力してください。';

    // 退会　パスワードエラー
    const ERR_CODE_41 = '入力されたパスワードが正しくありません。再度入力してください。 ';

    // アプリケーション削除エラー
    const ERR_CODE_42 = 'アプリケーション削除処理に失敗しました。<br>カスタマーサポートセンターまでご連絡ください。<br>';

    // API利用状況確認画面の有効期限切れ、あるいは、無効
    const ERR_CODE_43 = '無効なURLです。';

    // アクセスキーが無効
    const ERR_CODE_44 = 'アクセスキーが無効であるためご利用できません。';

    // アクセスキー延長失敗
    const ERR_CODE_45 = 'アクセスキーの有効期限延長に失敗しました。<br>カスタマーサポートセンターまでご連絡ください。<br>';

    // ディレクトリ作成失敗時の通知メッセージ
    const ERR_CODE_46 = 'ディレクトリ作成に失敗した。';

    // メール送信に失敗
    const ERR_CODE_47 = 'メール送信に失敗した。';

    // 項目にスペースがある時のエラー
    const ERR_CODE_48 = '漢字、ひらがな、全角カタカナで10文字以下、あるいは、半角英字20文字以下で入力してください。<br>スペースを入力しないでください。<br>';

    // DBからのデータ取得に失敗したエラー
    const ERR_CHECK_DATA = 'データチェックエラー';

    // NganVV_COMMENT_CODE_0128
    const ERR_TITLE_00 = 'リクエストエラー';

    // アプリケーション情報変更失敗
    const ERR_TITLE_01 = 'アプリケーション情報変更エラー';

    // アプリケーション削除失敗
    const ERR_TITLE_02 = 'アプリケーション削除エラー';

    // アプリケーション情報登録失敗
    const ERR_TITLE_03 = 'アプリケーション情報登録エラー';

    // ユーザー情報変更失敗
    const ERR_TITLE_04 = 'ユーザー情報変更エラー';

    // ログイン失敗
    const ERR_TITLE_05 = 'ログインエラー';

    // マイページ表示失敗
    const ERR_TITLE_06 = 'マイページ取得エラー';

    // ユーザー登録失敗
    const ERR_TITLE_07 = '新規アカウント発行エラー';

    // パスワードリマインド失敗
    const ERR_TITLE_08 = 'パスワード送信エラー';

    // 退会失敗
    const ERR_TITLE_09 = '退会エラー';

    // 退会失敗
    const ERR_TITLE_10 = 'API利用状況登録エラー';

    // メール送信エラー
    const ERR_SEND_MAIL = 'メール送信エラー';

    // ユーザー基本ＥＸエラー
    const ERR_USER_BASE_EX = 'DB接続エラー';

    // NganVV_COMMENT_CODE_0129
    const ERR_UNDEFINED_ROUTE = '不正な遷移です。';

    // URLとエラータイトルの紐付け
    const MAPPING_URL_TITLE = [
        CommonConst::APP_REGIST => self::ERR_TITLE_07,
        CommonConst::APP_CHANGE => self::ERR_TITLE_04,
        CommonConst::APP . CommonConst::APP_DELETE => self::ERR_TITLE_02,
        CommonConst::APP . CommonConst::APP_REGIST => self::ERR_TITLE_03,
        CommonConst::APP . CommonConst::APP_CHANGE => self::ERR_TITLE_01,
        CommonConst::APP_UNSUBSCRIBE => self::ERR_TITLE_09
    ];
}
