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
     * Lấy thông tin chi tiết transportation theo id
     *
     * @param $transportation
     * @return mixed
     */
    public function getTransDetailById($transportation) {
        $transportation = TransportationModel::find($transportation);
        return $transportation;
    }

    /**
     * Lấy danh sách transportation theo category id
     *
     * @param $category_id
     * @param int $limit
     * @return mixed
     */
    public function getListTransportationByCategoryId($category_id, $limit = 0) {
        $list_transportation = TransportationModel::where('transportation_category_id', $category_id)->paginate($limit);
        return $list_transportation;
    }
}