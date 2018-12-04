<?php
namespace App\Http\Services;

use App\Http\Models\sql\CategoryTour;
use App\Http\Models\sql\Tour;

class TourDetailService extends Service
{

    /**
     * @var Tour $tour
     */
    private $tour;

    /**
     * @var CategoryTour $category_tour
     */
    private $category_tour;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->tour = app(Tour::class);
        $this->category_tour = app(CategoryTour::class);
    }

    /**
     * Lấy thông tin hiển thị màn hình tour detail
     *
     * @param $tour_id
     * @return mixed
     * @throws \Exception
     */
    public function getData($tour_id)
    {
        // lấy thông tin hiển thị menu
        $data = parent::getMenuHeaderData();

        // lấy thông tin chi tiết tour
        $tour = $this->tour->getTourDetailById($tour_id);

        $category_tour_id = $tour['category_tour_id'];
        $ary_colum = [
            'tour_id',
            'tour_name',
            'update_datetime'
        ];

        // lấy danh sách tour có cùng category
        $list_tour = $this->tour->getListTourByCategoryId($ary_colum, $category_tour_id, $tour_id, 6);

        // lấy danh sách category tour
        $list_category_tours = $this->category_tour->getListCategoryTour(6);

        $data['tour'] = $tour;
        $data['list_tour'] = $list_tour;
        $data['list_category_tours'] = $list_category_tours;
        return $data;
    }
}
