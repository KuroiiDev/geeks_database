<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
            $user = Users::create($data);
            return response()->json([
                'status'=> 'success',
                'data'=> '',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error'=> $e->getMessage()],0);
        }
    }

    public function login()
    {
        try {
            $email = $this->postField('email');
            $password = $this->postField('password');

            $user = Users::with([])
                ->where('email', '=', $email)
                ->where('role', '=', 'USER')
                ->first();
            if (!$user) {
                return $this->jsonNotFoundResponse('user not found');
            }

            $isPasswordValid = Hash::check($password, $user->password);
            if (!$isPasswordValid) {
                return $this->jsonUnauthorizedResponse('password did not match');
            }

            return $this->jsonSuccessResponse('Login Success',$user);
        }catch (\Throwable $e) {
            return $this->jsonErrorResponse('internal server error '.$e->getMessage());
        }
    }

    public function staffLogin()
    {
        try {
            $email = $this->postField('email');
            $password = $this->postField('password');

            $user = Users::with([])
                ->where('email', '=', $email)
                ->where('role', '!=', 'USER')
                ->first();
            if (!$user) {
                return $this->jsonNotFoundResponse('user not found');
            }

            $isPasswordValid = Hash::check($password, $user->password);
            if (!$isPasswordValid) {
                return $this->jsonUnauthorizedResponse('password did not match');
            }

            return $this->jsonSuccessResponse('Login Success',$user);
        }catch (\Throwable $e) {
            return $this->jsonErrorResponse('internal server error '.$e->getMessage());
        }
    }

    
}
