<?php
namespace App\Http\Controllers;

use App\Http\Services\TourDetailService;
use Illuminate\Http\Request;

class TourDetailController extends Controller
{

    /**
     * サービス
     *
     * @var TourDetailService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(TourDetailService::class);
    }

    /**
     * Action được gọi đến khi vào xem thông tin chi chiết tour
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $tour_id = $request->get('tour_id');

            // lấy thông tin để hiển thị lên view
            $data = $this->service->getData($tour_id);

            return view('tour_detail')->with($data);
        } catch (\Exception $e) {
            return view('error')->with($data);
        }
    }
}
