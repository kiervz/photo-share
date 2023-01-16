<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Handle the users registration.
     *
     * @param  App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request['password'])
        ]);

        return $this->customResponse('Registered successfully.', [], Response::HTTP_CREATED);
    }
}
