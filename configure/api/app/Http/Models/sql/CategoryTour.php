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
}