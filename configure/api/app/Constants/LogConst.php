<?php
namespace App\Constants;

class LogConst
{
    // 退会ログ
    const LOG_UNSUBSCRIBE = 'ユーザー退会';

    /*
     * 仮登録のデータが削除されたときのログメッセージ
     * 仮登録完了日より後7日を超えた場合に本登録のURLをクリックする。
     */
    const LOG_EXPIRED_DELETE = 'ユーザー登録期限切れによる削除';

    // 仮登録完了時のログメッセージ
    const LOG_REGIST = 'ユーザー本登録';

    // アカウント登録ログ
    const LOG_TMP_REGIST = 'ユーザー仮登録';

    // アプリケーション削除時に出力されるログ
    const LOG_APP_DELETE = 'アプリケーション情報削除,?';

    // ログ（アプリケーション追加登録）
    const LOG_APP_REGIST = 'アプリケーション追加登録';

    // アクセスキー有効期限延長完了
    const LOG_APP_EXTENSION = 'アクセスキー有効期限延長, ';
}
