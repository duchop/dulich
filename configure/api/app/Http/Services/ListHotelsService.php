<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/5/2018
 * Time: 11:16 PM
 */

namespace App\Http\Services;

use App\Http\Models\sql\Hotel;

class ListHotelsService extends Service
{
    /**
     * @var Hotel $hotel
     */
    private $hotel;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->hotel = app(Hotel::class);
    }

    /**
     * Lấy thông tin hiển thị màn hình list hotel
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData()
    {
        $ary_request_all = request()->all();

        $category_hotel_id = $ary_request_all['category_hotel_id'];

        // lấy thông tin hiển thị menu
        $data = parent::getMenuHeaderData();

        // lấy thông tin chi tiết tour
        $list_hotels = $this->hotel->getListHotelsByCategoryId($category_hotel_id, 1);
        $data['list_hotels'] = $list_hotels;
        return $data;
    }

}