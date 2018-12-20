<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/20/2018
 * Time: 1:54 PM
 */

namespace App\Http\Models\sql;


use App\Http\Models\TransportationModel;

class Transportation
{
    /**
     * Lấy thông tin chi tiết tour theo id
     *
     * @param $hotel_id
     * @return mixed
     */
    public function getTransDetailById($hotel_id) {
        $hotel = TransportationModel::find($hotel_id);
        return $hotel;
    }

    /**
     * Lấy danh sách transportation theo category
     *
     * @param $category_id
     * @param int $limit
     * @return mixed
     */
    public function getListTransByCategoryId($category_id, $limit = 0) {
        $list_transportation = TransportationModel::where('transportation_category_id', $category_id)->paginate($limit);
        return $list_transportation;
    }
}