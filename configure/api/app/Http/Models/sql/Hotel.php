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
    public function getListHotel($limit, $offset = 0){
        $ary_colums = [
            'hotel_id',
            'hotel_name',
            'update_datetime'
        ];
        $ary_hotels = HotelModel::limit($limit)->offset($offset)->get($ary_colums);

        return $ary_hotels;
    }
}