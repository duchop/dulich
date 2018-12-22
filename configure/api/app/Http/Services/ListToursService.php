<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/5/2018
 * Time: 8:21 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\Tour;

class ListToursService extends Service
{
    /**
     * @var Tour $tour
     */
    private $tour;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->tour = app(Tour::class);
    }

    /**
     * Lấy thông tin hiển thị màn hình hotel detail
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData()
    {
        $ary_request_all = request()->all();

        $category_tour_id = $ary_request_all['category_tour_id'];

        // lấy thông tin hiển thị menu
        $data = parent::getMenuHeaderData();

        // lấy thông tin chi tiết tour
        $list_tours = $this->tour->getListToursByCategoryId($category_tour_id, 6);
        $data['list_tours'] = $list_tours;
        return $data;
    }
}