<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/20/2018
 * Time: 1:42 PM
 */

namespace App\Http\Controllers;


class ListTransportationController
{
    /**
     * サービス
     *
     * @var  ListToursService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(ListToursService::class);
    }

    /**
     * Action được gọi đến khi vào xem thông tin chi chiết tour
     *
     * @param Request $request
     */
    public function index()
    {
        // lấy thông tin để hiển thị lên view
        $data = $this->service->getData();

        return view('list_tours')->with($data);
    }
}