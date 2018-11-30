<?php
/**
 * ぐるなび例外クラス
 * ぐるなびの共通的な例外処理が発生した場合にthrowされます。
 *
 * @author Luvina
 */
namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{

    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * 文字列取得処理
     * オブジェクトの文字列表現を返却します。
     *
     * @return string このオブジェクトの文字列表現
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
