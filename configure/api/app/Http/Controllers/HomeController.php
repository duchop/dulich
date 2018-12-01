<?php
namespace App\Http\Controllers;

use App\Http\Models\sql\UserDbAccess;
use Illuminate\Http\Request;
use App\Constants\ErrorCodeConst;
use App\Http\Services\HomeService;
use App\Exceptions\ApiException;
use App\Constants\CommonConst;

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
     * ログイン画面にアクセス処理
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $data = $this->service->getData();

        return view('index')->with($data);
    }
}
