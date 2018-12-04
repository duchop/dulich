<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:57 PM
 */

namespace App\Http\Models\sql;


use App\Http\Models\CategoryTourModel;

class CategoryTour
{
    /**
     * lấy danh sách tên loại tour
     *
     * @return array
     */
    public function getListCategoryDailyTour() {
        $ary_category_tour = CategoryTourModel::where('category_tour_id', '!=', 5)->get(['category_name'])->toArray();

        return $ary_category_tour;
    }

    /**
     * Lấy danh sách category tour
     *
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getListCategoryTour($limit = 0, $offset = 0) {
        $ary_colums = [
            'category_tour_id',
            'category_name',
            'count_tour'
        ];
        $list_category_tours = CategoryTourModel::limit($limit)->offset($offset)->get($ary_colums);

        return $list_category_tours;
    }
}