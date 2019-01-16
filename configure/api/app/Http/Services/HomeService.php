<?php
namespace App\Http\Services;

use App\Http\Models\sql\Hotel;
use App\Http\Models\sql\Tour;

class HomeService extends Service
{

    /**
     * @var Tour $tour
     */
    private $tour;

    /**
     * @var Hotel $hotel
     */
    private $hotel;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->tour = app(Tour::class);
        $this->hotel = app(Hotel::class);
    }

    /**
     *
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function getData()
    {
        try {

            $data = parent::getMenuHeaderData();

            $ary_daily_tour = $this->tour->getListDailyTour(6);

            $ary_hotel = $this->hotel->getListHotel(6);

            $data['ary_daily_tour'] = $ary_daily_tour;
            $data['ary_hotel'] = $ary_hotel;

            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
