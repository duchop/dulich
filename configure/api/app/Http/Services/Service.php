<?php
namespace App\Http\Services;

use App\Http\Models\sql\CategoryTour;
use App\Http\Models\sql\HotelCategory;
use App\Http\Models\sql\Tour;

class Service
{

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {

    }

    /**
     * ログイン情報の確認
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function getMenuHeaderData()
    {
        try {
            $category_tour = app(CategoryTour::class);
            $tour = app(Tour::class);
            $hotel_category = app(HotelCategory::class);

            $ary_category_daily_tour = $category_tour->getListCategoryDailyTour();

            $ary_ha_long_tour = $tour->getListHaLongTour(6);

            $ary_category_hotel = $hotel_category->getListCategoryHotel();

            $data['ary_category_daily_tour'] = $ary_category_daily_tour;
            $data['ary_ha_long_tour'] = $ary_ha_long_tour;
            $data['ary_category_hotel'] = $ary_category_hotel;

            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
