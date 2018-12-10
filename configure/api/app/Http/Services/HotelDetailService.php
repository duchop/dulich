<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/4/2018
 * Time: 1:49 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\Hotel;
use App\Http\Models\sql\HotelCategory;

class HotelDetailService extends Service
{
    /**
     * @var Hotel $hotel
     */
    private $hotel;

    /**
     * @var HotelCategory $hotel_category
     */
    private $hotel_category;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->hotel = app(Hotel::class);
        $this->hotel_category = app(HotelCategory::class);
    }

    /**
     * Lấy thông tin hiển thị màn hình hotel detail
     *
     * @param $hotel_id
     * @return mixed
     * @throws \Exception
     */
    public function getData($hotel_id)
    {
        // lấy thông tin hiển thị menu
        $data = parent::getMenuHeaderData();

        // lấy thông tin chi tiết hotel
        $hotel = $this->hotel->getHotelDetailById($hotel_id);

        $data['hotel'] = $hotel;
        return $data;
    }
}