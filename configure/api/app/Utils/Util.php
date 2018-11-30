<?php
namespace App\Utils;

class Util
{

    // 数字
    const CHECKTEXTINC_CHR_TYPE_NUMBER = 1;

    // アルファベット
    const CHECKTEXTINC_CHR_TYPE_ALPHABET = 2;

    // 増分0(連続文字列のみ 例："1111")
    const CHECKTEXTINC_INCREASE_MODE_ZERO = 0;

    // 増分1以内(例："1111" "1234" "9876")
    const CHECKTEXTINC_INCREASE_MODE_ONE = 1;

    // パスワードハッシュ追加文字数
    const PASS_ADD_NUM = 5;

    // パスワードASCII値シフト数
    const PASS_ASCII_SHIFT_NUM = 16;

    /**
     * 日本語文字列の文字コード判定(ASCII/JIS/eucJP-win/SJIS-win/UTF-8 のみ)
     */
    public static function detectEncodingJA($str)
    {
        $enc = @mb_detect_encoding($str, 'ASCII,JIS,eucJP-win,UTF-8,SJIS-win');

        switch ($enc) {
            case false:
            case 'ASCII':
            case 'JIS':
            case 'UTF-8':
                break;
            case 'eucJP-win':
                // ここで eucJP-win を検出した場合、eucJP-win として判定
                if (@mb_detect_encoding($str, 'SJIS-win,UTF-8,eucJP-win') === 'eucJP-win') {
                    break;
                }
                $_hint = "\xbf\xfd" . $str; // "\xbf\xfd" : EUC-JP "雀"

                // EUC-JP -> UTF-8 変換時にマッピングが変更される文字を削除( ≒ ≡ ∫ など)
                mb_regex_encoding('EUC-JP');
                $_hint = mb_ereg_replace("\xad(?:\xe2|\xf5|\xf6|\xf7|\xfa|\xfb|\xfc|\xf0|\xf1|\xf2)", '', $_hint);

                $_tmp = mb_convert_encoding($_hint, 'UTF-8', 'eucJP-win');
                $_tmp2 = mb_convert_encoding($_tmp, 'eucJP-win', 'UTF-8');

                if ($_tmp2 === $_hint) {
                    // 例外処理( EUC-JP 以外と認識する範囲 )
                    if (! preg_match('/^(?:' . '[\x8E\xE0-\xE9][\x80-\xFC]|\xEA[\x80-\xA4]|' .
                        '\x8F[\xB0-\xEF][\xE0-\xEF][\x40-\x7F]|' .
                        '\xF8[\x9F-\xFC]|\xF9[\x40-\x49\x50-\x52\x55-\x57\x5B-\x5E\x72-\x7E\x80-\xB0\xB1-\xFC]|' .
                        '[\x00-\x7E]' . ')+$/', $str) &&

                    // UTF-8 と重なる範囲(全角英数字|漢字|1バイト文字)
                    ! preg_match('/^(?:' . '\xEF\xBC[\xA1-\xBA]|[\x00-\x7E]|' .
                        '[\xE4-\xE9][\x8E-\x8F\xA1-\xBF][\x8F\xA0-\xEF]|' . '[\x00-\x7E]' . ')+$/', $str)) {
                        // 条件式の範囲に入らなかった場合は、eucJP-win として検出
                        break;
                    }
                    // 例外処理2(一部の頻度の多そうな熟語は eucJP-win として判定)
                    // (珈琲|琥珀|瑪瑙|癇癪|碼碯|耄碌|膀胱|蒟蒻|薔薇|蜻蛉)
                    mb_regex_encoding('eucJP-win');
                    if (mb_ereg('^(?:' .
                        '\xE0\xDD\xE0\xEA|\xE0\xE8\xE0\xE1|\xE0\xF5\xE0\xEF|\xE1\xF2\xE1\xFB|' .
                        '\xE2\xFB\xE2\xF5|\xE6\xCE\xE2\xF1|\xE7\xAF\xE6\xF9|\xE8\xE7\xE8\xEA|' .
                        '\xE9\xAC\xE9\xAF|\xE9\xF1\xE9\xD9|[\x00-\x7E]' . ')+$', $str)) {
                        break;
                    }
                }
                break;
            default:
                // ここで SJIS-win と判断された場合は、文字コードは SJIS-win として判定
                $enc = @mb_detect_encoding($str, 'UTF-8,SJIS-win');
                if ($enc === 'SJIS-win') {
                    break;
                }
                // デフォルトとして SJIS-win を設定
                $enc = 'SJIS-win';

                $_hint = "\xe9\x9b\x80" . $str; // "\xe9\x9b\x80" : UTF-8 "雀"

                // 変換時にマッピングが変更される文字を調整
                mb_regex_encoding('UTF-8');
                $_hint = mb_ereg_replace("\xe3\x80\x9c", "\xef\xbd\x9e", $_hint);
                $_hint = mb_ereg_replace("\xe2\x88\x92", "\xe3\x83\xbc", $_hint);
                $_hint = mb_ereg_replace("\xe2\x80\x96", "\xe2\x88\xa5", $_hint);

                $_tmp = mb_convert_encoding($_hint, 'SJIS-win', 'UTF-8');
                $_tmp2 = mb_convert_encoding($_tmp, 'UTF-8', 'SJIS-win');

                if ($_tmp2 === $_hint) {
                    $enc = 'UTF-8';
                }
                // UTF-8 と SJIS 2文字が重なる範囲への対処(SJIS を優先)
                if (preg_match('/^(?:[\xE4-\xE9][\x80-\xBF][\x80-\x9F][\x00-\x7F])+/', $str)) {
                    $enc = 'SJIS-win';
                }
        }
        return $enc;
    }

    /**
     * 入力された文字列が連続文字列、等増分文字列でないかをチェック
     * (マルチバイト非対応)
     *
     * @access private
     * @param string $content
     *            文字列
     * @param integer $chr_type
     *            チェックする文字タイプ(組み合わせ指定可)
     *            CHECKTEXTINC_CHR_TYPE_NUMBER＝数字
     *            CHECKTEXTINC_CHR_TYPE_ALPHABET＝アルファベット
     * @param integer $increase_mode
     *            チェック増分指定
     *            CHECKTEXTINC_INCREASE_MODE_ZERO＝増分0(連続文字列)
     *            CHECKTEXTINC_INCREASE_MODE_ONE＝増分1以内
     *            CHECKTEXTINC_INCREASE_MODE_ALL＝増分なんでも
     * @return boolean 連続文字列、等増分文字列である＝false そうではない＝true
     */
    public static function checkTextIncrease($content, $chr_type = (1 | 2), $increase_mode = -1)
    {
        $len = strlen($content);

        // 文字数が1以下なら、OK
        if ($len <= 1) {
            return true;
        }

        // 文字列をアスキーコードに変換
        for ($i = 0; $i < $len; $i ++) {
            $code[] = ord(substr($content, $i, 1));
        }

        // 文字タイプ判定
        $match_flag = false;
        if ($chr_type & self::CHECKTEXTINC_CHR_TYPE_NUMBER) {
            // 数字のみ
            $match_flag |= preg_match("/^\d+$/", $content);
        }
        if ($chr_type & self::CHECKTEXTINC_CHR_TYPE_ALPHABET) {
            // アルファベットのみ
            $match_flag |= preg_match("/^[A-Za-z]+$/", $content);
        }

        if (! $match_flag) {
            // マッチしなければOK
            return true;
        } else {
            // マッチした場合

            // 最初(1文字目xA群文字目)の増分を取得
            $old_diff = $code[0] - $code[1];

            // 文字列を頭から順に増分チェック
            $cnt = count($code);
            for ($i = 1; $i < $cnt; $i ++) {
                $diff = $code[$i - 1] - $code[$i];
                if ($i != 1 && $diff != $old_diff) {
                    // 増分が最初と異なる部分が見つかったら、OK
                    return true;
                }
            }

            // 増分によって処理をわける
            if ($old_diff == 0) {
                // 増分0の場合
                return false;
            } elseif ($old_diff == 1 || $old_diff == - 1) {
                // 増分1の場合
                if ($increase_mode == self::CHECKTEXTINC_INCREASE_MODE_ZERO) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // 増分がそれ以外の場合
                if ($increase_mode == self::CHECKTEXTINC_INCREASE_MODE_ZERO ||
                    $increase_mode == self::CHECKTEXTINC_INCREASE_MODE_ONE) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * パスワード暗号化
     *
     * @param String $pass
     *            パスワード
     * @return String $hashPass 暗号化パスワード
     */
    public static function passEncryption($pass)
    {
        $token = "abcdefghijklmnopqrstuvwxfzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $hash_pass = "";

        // ダミー文字を先頭に付加
        for ($i = 0; $i < self::PASS_ADD_NUM; $i ++) {
            $pass = substr($token, rand(0, strlen($token)) - 1, 1) . $pass;
        }

        // 各文字ASCIIで-PASS_ASCII_SHIFT_NUM
        for ($i = 0; $i < strlen($pass); $i ++) {
            $hash_pass .= chr(ord(substr($pass, $i, 1)) - self::PASS_ASCII_SHIFT_NUM);
        }

        return $hash_pass;
    }

    /**
     * パスワード復号化
     *
     * @param String $hash_pass
     *            暗号化パスワード
     * @return String $pass 復号化パスワード
     */
    public static function passDecryption($hash_pass)
    {
        $pass = '';
        $hash_pass = substr($hash_pass, self::PASS_ADD_NUM);

        // 各文字ASCIIで+PASS_ASCII_SHIFT_NUM
        for ($i = 0; $i < strlen($hash_pass); $i ++) {
            $pass .= chr(ord(substr($hash_pass, $i, 1)) + self::PASS_ASCII_SHIFT_NUM);
        }

        return $pass;
    }

    /**
     * 機種依存文字ローマ数字の置換
     *
     * @param String $str
     * @return String
     */
    public static function romaReplace($str)
    {
        $ary = [
            "\xFC\xF1" => "i",
            "\xFC\xF2" => "ii",
            "\xFC\xF3" => "iii",
            "\xFC\xF4" => "iv",
            "\xFC\xF5" => "v",
            "\xFC\xF6" => "vi",
            "\xFC\xF7" => "vii",
            "\xFC\xF8" => "viii",
            "\xFC\xF9" => "ix",
            "\xFC\xFA" => "x",
            "\xAD\xB5" => "I",
            "\xAD\xB6" => "II",
            "\xAD\xB7" => "III",
            "\xAD\xB8" => "IV",
            "\xAD\xB9" => "V",
            "\xAD\xBA" => "VI",
            "\xAD\xBB" => "VII",
            "\xAD\xBC" => "VIII",
            "\xAD\xBD" => "IX",
            "\xAD\xBE" => "X"
        ];

        return strtr($str, $ary);
    }

    /**
     * マッピングチェック
     *
     * @param String $str
     *            文字列
     * @return $illStr or TRUE
     */
    public function mappingCharCheck($str)
    {
        $sjis = mb_convert_encoding($str, 'SJIS', 'UTF-8');
        $aftar_str = mb_convert_encoding($sjis, 'UTF-8', 'SJIS');
        if ($str != $aftar_str) {
            $ill_str = '';
            // 文字数取得
            $aftar_str_num = mb_strlen($str);
            for ($i = 0; $i <= $aftar_str_num; $i ++) {
                // 文字化けしてたら、化ける前の文字列取得
                $moji = mb_substr($str, $i, 1);
                $moji2 = mb_substr($aftar_str, $i, 1);
                if ($moji != $moji2) {
                    $ill_str .= $moji;
                }
            }
            return $ill_str;
        } else {
            return true;
        }
    }

    /**
     * パスワードを伏字にする
     *
     * @param String $pass
     * @return String
     */
    public static function escapePass($pass)
    {
        $str_num = strlen($pass);
        $escape_pass = str_repeat('*', $str_num);
        return $escape_pass;
    }

    /**
     * アクセスキーを格納しているフォルダのフォルダ名を作成する。
     *
     * @param
     *            $value
     * @return bool|string
     */
    public static function getMD5Hash($value)
    {
        $ret = "";
        if (! is_null($value) || $value != '') {
            $ret = substr(md5($value), 0, 2);
        }
        return $ret;
    }
}
