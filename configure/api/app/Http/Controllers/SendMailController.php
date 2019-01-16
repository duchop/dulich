<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/15/2019
 * Time: 3:07 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class SendMailController extends Controller
{
    /**
     * Action được gọi đến khi vào trang chủ
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $email = $request->get('email');

        return response()->json([
            'error' => true,
            'email' => $email
        ], 200);
    }
}