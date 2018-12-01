<?php
namespace App\Http\Services;

use App\Constants\ErrorCodeConst;
use App\Http\Models\ApiUserModel;
use App\Http\Models\sql\CategoryTour;
use App\Http\Models\sql\HotelCategory;
use App\Http\Models\sql\Tour;
use App\Utils\UserBaseEX;
use App\Utils\Util;
use App\Exceptions\ApiException;
use App\Utils\CookieChecker;

class HomeService
{

    /**
     * @var CategoryTour $category_tour
     */
    private $category_tour;

    /**
     * @var Tour $tour
     */
    private $tour;

    /**
     * @var $var HotelCategory
     */
    private $hotel_category;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->category_tour = app(CategoryTour::class);
        $this->tour = app(Tour::class);
        $this->hotel_category = app(HotelCategory::class);
    }

    /**
     * ログイン情報の確認
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function getData()
    {
        try {
            $ary_category_daily_tour = $this->category_tour->getListDailyTour();

            $ary_ha_long_tour = $this->tour->getListHaLongTour();

            $ary_category_hotel = $this->hotel_category->getListCategoryHotel();

            $ary_daily_tour = $this->tour->getListDailyTour(6);
//            foreach ($ary_daily_tour as $a) {
//                echo ('<pre>');
//                echo($a->imageRelation['0']->image);
//            }
//die();
            $data['ary_category_daily_tour'] = $ary_category_daily_tour;
            $data['ary_ha_long_tour'] = $ary_ha_long_tour;
            $data['ary_category_hotel'] = $ary_category_hotel;
            $data['ary_daily_tour'] = $ary_daily_tour;

            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * ユーザーの使用状態を確認する。
     *
     * @param $api_user_id ユーザーのID
     */
    private function checkLogin($api_user_id)
    {
        try {
            $ary_column = [
                'API_USER_ID',
                'STATUS'
            ];
            $result = ApiUserModel::where('API_USER_ID', $api_user_id)->first($ary_column);

            if (! $result) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            throw new \Exception(ErrorCodeConst::ERR_CODE_09);
        }

        // ユーザーの使用状態を確認する。
        if ($result['STATUS'] == 0) {
            throw new ApiException(ErrorCodeConst::ERR_CODE_33);
        } elseif ($result['STATUS'] == 2) {
            throw new ApiException(ErrorCodeConst::ERR_CODE_34);
        }
    }
}
