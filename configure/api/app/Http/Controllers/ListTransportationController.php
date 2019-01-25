<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/20/2018
 * Time: 1:42 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\ListTransportationService;

class ListTransportationController extends Controller
{
    /**
     * サービス
     *
     * @var  ListTransportationService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(ListTransportationService::class);
    }

    /**
     * Action được gọi đến khi xem danh sách list transportation
     *
     * @param Request $request
     */
    public function index()
    {
        try {
            // lấy thông tin để hiển thị lên view
            $data = $this->service->getData();

            return view('list_transportation')->with($data);
        } catch (\Exception $e) {
            return view('error')->with($data);
        }
    }
}