<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/22/2018
 * Time: 12:31 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\TransportationDetailService;
use Illuminate\Http\Request;

class TransportationDetailController extends Controller
{
    /**
     * サービス
     *
     * @var TransportationDetailService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(TransportationDetailService::class);
    }

    /**
     * Action được gọi đến khi vào xem thông tin chi chiết tour
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $transportation_id = $request->get('transportation_id');

            // lấy thông tin để hiển thị lên view
            $data = $this->service->getData($transportation_id);

            return view('transportation_detail')->with($data);
        } catch (\Exception $e) {
            return view('error')->with($data);
        }
    }
}