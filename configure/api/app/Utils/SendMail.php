<?php
namespace App\Utils;

use App\Constants\MailConst;

class SendMail
{

    /*
     * メール送信
     * @param String $subject 件名
     * @param String $mail メールアドレス
     * @param String $body 本文
     * @return Boolean
     */
    public function send($subject, $mail, $body)
    {
        echo $body;
        // UTF-8はこれをやらないと文字化けする
        mb_internal_encoding("UTF-8");

        // デバッグモードONならメール送信せず
        if (MailConst::DEBUG_MODE == 'ON') {
            return true;
        }

        $header = $this->getDefHeader();
        $body = mb_convert_encoding($body, 'ISO-2022-JP', 'auto');

        // Return-Pathの設定
        $r_path = "-f " . MailConst::MAIL_R_PATH;

        // メールを送信する。
        if (! mail(
            $this->headerBase64Encode($mail),
            mb_encode_mimeheader($subject, 'ISO-2022-JP', 'B', "\n"),
            $body,
            $this->headerBase64Encode($header), $r_path)
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * デフォルトメールヘッダーの設定
     *
     * @return String
     */
    public function getDefHeader()
    {
        // メールヘッダ設定
        $header = "";
        $ref = '';
        $host = '';
        $u_agent = '';
        $r_host = '';

        if (isset($_SERVER{'HTTP_REFERER'})) {
            $ref = $_SERVER{'HTTP_REFERER'};
        }

        if (isset($_SERVER{'REMOTE_HOST'})) {
            $host = $_SERVER{'REMOTE_HOST'};
        }

        if (isset($_SERVER{'HTTP_USER_AGENT'})) {
            $u_agent = $_SERVER{'HTTP_USER_AGENT'};
        }

        if (isset($_SERVER{'REMOTE_ADDR'})) {
            $r_host = $_SERVER{'REMOTE_ADDR'};
        }

        $header .= "From: " . MailConst::MAIL_FROM . "\n";
        $header .= "X-HTTP_REFERER:" . $ref . "\n";
        $header .= "X-HTTP-User-Agent:" . $u_agent . "\n";
        $header .= "X-Remote-host:" . $host . "[" . $r_host . "]" . "\n";
        $header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
        $header .= "Content-Transfer-Encoding: 7bit";
        return $header;
    }

    /**
     * 日本語だけをbase64にエンコードする
     *
     * @param String $str
     * @return String
     */
    public function headerBase64Encode($str, $is_separate_line = false)
    {
        $str_len = mb_strlen($str);
        $encoded_string = '';
        $substring = '';
        $before_is_MB_char = false;
        $ch = "";
        $is_first_line = true;
        for ($i = 0; $i <= $str_len; $i ++) {
            if ($i == $str_len) {
                $this_is_MB_char = ! $before_is_MB_char;
            } else {
                $ch = mb_substr($str, $i, 1);
                $this_is_MB_char = (ord($ch) > 127);
            } // End of else
            if (($this_is_MB_char != $before_is_MB_char) && ($substring != '')) {
                if ($is_separate_line && (! $is_first_line)) {
                    $encoded_string .= "\t";
                }

                // ASCII文字の場合
                if ($this_is_MB_char) {
                    $encoded_string .= $substring;
                } else {
                    // 日本語の場合
                    $jis_seq = mb_convert_encoding($substring, 'ISO-2022-JP');
                    $jis_seq .= chr(27) . '(B';
                    $b_encoded = base64_encode($jis_seq);
                    $encoded_string .= "=?ISO-2022-JP?B?$b_encoded?=";
                }

                if ($is_separate_line) {
                    $encoded_string .= "\n";
                }

                $substring = '';
                $is_first_line = false;
            }

            $substring .= $ch;
            $before_is_MB_char = $this_is_MB_char;
        }

        return $encoded_string;
    }
}
