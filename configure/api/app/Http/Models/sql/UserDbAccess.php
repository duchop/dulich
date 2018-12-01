<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models\sql;

use App\Http\Models\UserModel;

class UserDbAccess
{
    public function getUserInfo($user_name)
    {
        $ary_colums = [
            'mail',
            'password'
        ];
        $user = UserModel::where('user_name', $user_name)->first($ary_colums);

        return $user;
    }
}