<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/2/2018
 * Time: 12:21 PM
 */

namespace App\Http\Models\sql;

use App\Http\Models\HotelModel;

class Hotel
{
    /**
     * lấy danh sách hotel
     *
     * @param $limit
     * @param int $offset
     * @return mixed
     */
    public function getListHotel($limit = 0, $offset = 0){
        $ary_colums = [
            'hotel_id',
            'hotel_name',
            'update_datetime'
        ];
        $ary_hotels = HotelModel::limit($limit)->offset($offset)->get($ary_colums);

        return $ary_hotels;
    }

    /**
     * Lấy thông tin chi tiết tour theo id
     *
     * @param $hotel_id
     * @return mixed
     */
    public function getHotelDetailById($hotel_id) {
        $hotel = HotelModel::find($hotel_id);
        return $hotel;
    }

    /**
     * Lấy danh sách hotel theo category
     *
     * @param $category_hotel_id
     * @param int $limit
     * @return mixed
     */
    public function getListHotelsByCategoryId($category_hotel_id, $limit = 0) {
        $list_hotels = HotelModel::where('hotel_category_id', $category_hotel_id)->paginate($limit);
        return $list_hotels;
    }
}