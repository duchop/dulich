<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/22/2018
 * Time: 1:12 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\Transportation;

class TransportationDetailService extends Service
{
    /**
     * @var Transportation $transportation
     */
    private $transportation;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->transportation = app(Transportation::class);
    }

    /**
     * Lấy thông tin hiển thị màn hình hotel transportation
     *
     * @param $transportation_id
     * @return mixed
     * @throws \Exception
     */
    public function getData($transportation_id)
    {
        // lấy thông tin hiển thị menu
        $data = parent::getMenuHeaderData();

        // lấy thông tin chi tiết hotel
        $transportation = $this->transportation->getTransDetailById($transportation_id);

        $data['transportation'] = $transportation;
        return $data;
    }
}