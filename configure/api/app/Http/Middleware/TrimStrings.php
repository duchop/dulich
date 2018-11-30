<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use App\Utils\Util;
use App\Constants\RegularConst;

class TrimStrings extends Middleware
{

    /**
     * 置換処理必要ないパラメータ設定
     *
     * @var array
     */
    protected $except = [
        'pass1',
        'pass2'
    ];

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        $value = $this->getParamTrim($value);

        return is_string($value) ? trim($value) : $value;
    }

    /*
     * ポストデータトリム & 半角カナ→全角カナに変換 & 「'」→「’」に変換
     * @param String $str パラメータ
     * @return String $str トリムしたパラメータ
     */
    private function getParamTrim($str)
    {
        // 帯広市と入力すると「広市」で置換にひっかかり変になるのでコメントアウト 07.05.10 Sano
        $str = mb_convert_encoding($str, "UTF-8", Util::detectEncodingJA($str));
        $str = preg_replace(RegularConst::CHECK_PARAM_TRIM, '', $str);

        // 半角カタカナかどうか
        mb_regex_encoding('UTF-8');
        if (mb_ereg(RegularConst::CHECK_HANKAKU_KANA, $str)) {
            $str = mb_convert_kana($str, "K", Util::detectEncodingJA($str));
        }

        $str = str_replace("'", "’", $str);
        return $str;
    }
}
