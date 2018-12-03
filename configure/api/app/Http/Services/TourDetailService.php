<?php
namespace App\Http\Services;

use App\Http\Models\sql\Tour;

class TourDetailService extends Service
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
     * Lấy thông tin hiển thị màn hình tour detail
     *
     * @param $tour_id
     * @return mixed
     * @throws \Exception
     */
    public function getData($tour_id)
    {
        $data = parent::getMenuHeaderData();

        $tour = $this->tour->getTourDetailById($tour_id);

        $category_tour_id = $tour['category_tour_id'];
        $ary_colum = [
            'tour_id',
            'tour_name',
            'update_datetime'
        ];

        $list_tour = $this->tour->getListTourByCategoryId($ary_colum, $category_tour_id, $tour_id, 6);
//        dd($tour->getCategoryTour['category_name']);
        $data['tour'] = $tour;
        $data['list_tour'] = $list_tour;
        return $data;
    }
}
