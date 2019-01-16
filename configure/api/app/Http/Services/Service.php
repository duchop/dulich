<?php
namespace App\Http\Services;

use App\Http\Models\sql\CategoryTour;
use App\Http\Models\sql\HotelCategory;
use App\Http\Models\sql\Tour;
use App\Http\Models\sql\User;

class Service
{

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
            $hotel_category = app(HotelCategory::class);
            $tour = app(Tour::class);
            $user = app(User::class);

            $ary_category_daily_tour = $category_tour->getListCategoryDailyTour();

            $ary_ha_long_tour = $tour->getListHaLongTour(6);

            $ary_category_hotel = $hotel_category->getListCategoryHotel();

            $user_info = $user->getUserInfo(1);

            $data['ary_category_daily_tour'] = $ary_category_daily_tour;
            $data['ary_ha_long_tour'] = $ary_ha_long_tour;
            $data['ary_category_hotel'] = $ary_category_hotel;
            $data['user_info'] = $user_info;
            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
