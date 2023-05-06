<?php

namespace App\Http\Controllers;

use App\Http\Requests\utils\LoginRequest;
use App\Http\Requests\utils\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalResponse;

class UtilesController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', "like", $request->email)->first();

        if (!Hash::check($request->password, $user->password))
            return LocalResponse::returnError('البيانات غير متوافقة', 400, [
                'password' => ['كملة السر خطأ']
            ]);

        $token = $user->getToken();
        return LocalResponse::returnData("login", [
            'token' => $token,
            'user' => $user
        ]);
    }
    public function register(RegisterRequest $request)
    {
        $customer = User::create($request->values());
        $token = $customer->getToken();
        return LocalResponse::returnData('register', [
            'user' => $customer,
            'token' => $token
        ]);
    }
}
