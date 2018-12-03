<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\HomeService;

class HomeController extends Controller
{

    /**
     * Service
     *
     * @var HomeService $service
     */
    private $service;

    /**
     * Hàm khởi tạo của controller
     */
    public function __construct()
    {
        $this->service = app(HomeService::class);
    }

    /**
     * Action được gọi đến khi vào trang chủ
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $data = $this->service->getData();

        return view('index')->with($data);
    }
}
