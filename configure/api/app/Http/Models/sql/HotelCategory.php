<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/1/2018
 * Time: 11:19 PM
 */

namespace App\Http\Models\sql;


use App\Http\Models\HotelCategoryModel;

class HotelCategory
{

    /**
     * lấy danh sách catrgory hotel
     *
     * @return HotelCategoryModel[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListCategoryHotel() {
        $ary_category_hotel = HotelCategoryModel::all(['hotel_category_name']);
        return $ary_category_hotel;
    }
}