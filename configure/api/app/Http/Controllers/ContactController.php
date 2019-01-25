<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/14/2019
 * Time: 4:10 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Service
     *
     * @var ContactService $service
     */
    private $service;

    /**
     * Hàm khởi tạo của controller
     */
    public function __construct()
    {
        $this->service = app(ContactService::class);
    }

    /**
     * Action được gọi đến khi vào trang chủ
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $data = $this->service->getData(1);

            return view('contact')->with($data);
        } catch (\Exception $ex) {
            return view('error')->with($data);
        }
    }
}