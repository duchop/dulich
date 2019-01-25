<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/4/2018
 * Time: 1:48 PM
 */

namespace App\Http\Controllers;


use App\Http\Services\HotelDetailService;
use Illuminate\Http\Request;

class HotelDetailController extends Controller
{
    /**
     * サービス
     *
     * @var HotelDetailService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(HotelDetailService::class);
    }

    /**
     * Action được gọi đến khi vào xem thông tin chi chiết hotel
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $hotel_id = $request->get('hotel_id');

            // lấy thông tin để hiển thị lên view
            $data = $this->service->getData($hotel_id);

            return view('hotel_detail')->with($data);
        } catch (\Exception $e) {
            return view('error')->with($data);
        }
    }
}