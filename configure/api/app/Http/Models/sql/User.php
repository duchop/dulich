<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models\sql;

use App\Http\Models\UserModel;

class User
{
    /**
     * Lấy thông tin user
     *
     * @param $user_name
     * @return mixed
     */
    public function getUserInfo($user_id)
    {
        $ary_colums = [
            'user_id',
            'user_name',
            'email',
            'area',
            'password',
            'number_phone',
            'address_office'
        ];
        $user = UserModel::where('user_id', $user_id)->first($ary_colums);

        return $user;
    }
}