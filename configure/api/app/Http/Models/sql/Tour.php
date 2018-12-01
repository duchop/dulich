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
    public function getListHaLongTour() {
        $ary_colums = [
            'category_tour_id',
            'tour_name',
        ];
        $ary_tours = TourModel::all($ary_colums);
        return $ary_tours;
    }

    public function getListDailyTour($limit) {
        $ary_colums = [
            'tour_id',
            'tour_name',
            'price',
            'update_datetime'
        ];
        $ary_daily_tours = TourModel::where('category_tour_id','!=', 5)->limit($limit)->get($ary_colums);
        return $ary_daily_tours;
    }
}