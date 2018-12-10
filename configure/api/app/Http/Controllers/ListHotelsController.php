<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/5/2018
 * Time: 11:14 PM
 */

namespace App\Http\Controllers;


use App\Http\Services\ListHotelsService;
use Illuminate\Http\Request;

class ListHotelsController extends Controller
{
    /**
     * サービス
     *
     * @var  ListHotelsService $service
     */
    private $service;

    /**
     * コントローラ初期化
     */
    public function __construct()
    {
        $this->service = app(ListHotelsService::class);
    }

    /**
     * Action được gọi đến khi vào xem thông tin chi chiết tour
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // lấy thông tin để hiển thị lên view
        $data = $this->service->getData();

        return view('list_hotels')->with($data);
    }
}