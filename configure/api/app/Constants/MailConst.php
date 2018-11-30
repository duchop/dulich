<?php
/**
 * メール
 *
 * @author Luvina
 */
namespace App\Constants;

class MailConst
{

    // デバッグモード(ON, OFF)
    const DEBUG_MODE = 'ON';

    // 送信元
    const MAIL_FROM = 'phamduyhai@luvina.net';

    const MAIL_R_PATH = 'phamduyhai@luvina.net';

    // Return-Path
    const KEY_SUBJECT = '【ぐるなびAPI】ユーザー登録手続き';

    // キー送付時メールタイトル
    const ACCESS_KEY_SUBJECT = '【ぐるなびAPI】ユーザー登録手続き完了';

    // アクセスキー送付時メールタイトル
    const ID_PASS_SUBJECT = '【ぐるなびAPI】パスワード送付';

    // ユーザーID、パスワード送付時メールタイトル
    const MAIL_UNSUB_SUBJECT = '【ぐるなびAPI】退会手続き完了';

    const MAIL_APP_REGIST_SUBJECT = '【ぐるなびAPI】アプリケーション新規登録完了';

    const MAIL_APP_DELETE_SUBJECT = '【ぐるなびAPI】アプリケーション情報削除完了';

    const MAIL_APP_CHECK_DONE_SUBJECT = '【ぐるなびAPI】APIご利用状況登録完了';

    const MAIL_KEY_EXPIRY_EXTENSION = '【ぐるなびAPI】アクセスキー利用期限延長完了';

    const KEY_SUBJECT_EN = ' [Gurunavi API] user registration procedure';

    const ACCESS_KEY_SUBJECT_EN = '[Gurunavi API] user registration procedure complete';

    const ID_PASS_SUBJECT_EN = '[Gurunavi API] send password';
}
