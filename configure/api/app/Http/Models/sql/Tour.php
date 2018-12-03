<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 4:58 PM
 */

namespace App\Http\Models\sql;

use App\Http\Models\TourModel;

class Tour
{
    /**
     * Lây danh sách tour Hạ Long
     *
     * @param $category_tour_id
     * @return mixed
     */
    public function getListHaLongTour($limit, $offset = 0) {
        $ary_colums = [
            'tour_id',
            'tour_name',
            'tour_time',
            'price',
            'update_datetime'
        ];
        $ary_tours = TourModel::where('category_tour_id', 5)->limit($limit)->offset($offset)->get($ary_colums);
        return $ary_tours;
    }

    /**
     * Lấy danh sách dailytour
     *
     * @param $limit
     * @param int $offset
     * @return mixed
     */
    public function getListDailyTour($limit, $offset = 0) {
        $ary_colums = [
            'tour_id',
            'tour_name',
            'tour_time',
            'price',
            'update_datetime'
        ];
        $ary_daily_tours = TourModel::where('category_tour_id','!=', 5)->limit($limit)->offset($offset)->get($ary_colums);
        return $ary_daily_tours;
    }

    /**
     * Lấy thông tin chi tiết tour theo id
     *
     * @param $tour_id
     * @return mixed
     */
    public function getTourDetailById($tour_id) {
        $tour = TourModel::find($tour_id);
        return $tour;
    }

    /**
     * Lấy danh sách tour theo category
     *
     * @param $category_tour_id
     * @return mixed
     */
    public function getListTourByCategoryId($ary_colums, $category_tour_id, $tour_id, $limit = 0, $offset = 0) {
        $list_tours = TourModel::where('category_tour_id', $category_tour_id)->where('tour_id', '!=', $tour_id)
            ->limit($limit)->offset($offset)->get($ary_colums);
        return $list_tours;
    }
}