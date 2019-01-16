<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/14/2019
 * Time: 4:11 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\User;

class ContactService extends Service
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->user = app(User::class);
    }

    /**
     * Lấy thông tin user
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function getData($user_id)
    {
        try {
            $data = parent::getMenuHeaderData();

            $user_info = $this->user->getUserInfo($user_id);

            $data['user_info'] = $user_info;

            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}