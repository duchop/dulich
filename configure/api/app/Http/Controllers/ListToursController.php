<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/5/2018
 * Time: 8:19 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\ListToursService;
use Illuminate\Http\Request;

class ListToursController extends Controller
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
        try {
            // lấy thông tin để hiển thị lên view
            $data = $this->service->getData();

            return view('list_tours')->with($data);
        } catch (\Exception $e) {
            return view('error')->with($data);
        }
    }
}