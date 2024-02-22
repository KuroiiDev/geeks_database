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
                'data'=> $user,
            ],201);
        } catch (\Throwable $e) {
            return response()->json([
                'status'=> 'failed',
                'message'=> $e->getMessage()
            ],500);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email'=>'required',
                'password'=>'required'
            ]);

            $user = Users::with([])
                ->where('email', '=', $data['email'])
                ->where('role', '=', 'USER')
                ->first();
            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User Not Found'
                ],401);
            }

            $isPasswordValid = Hash::check($data['password'], $user->password);
            if (!$isPasswordValid) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Password Not Match'
                ],401);
            }

            return response()->json([
                'status' => 'success',
                'data' => $user
            ],200);
        }catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                    'message' => $e->getMessage()
            ],500);
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
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Account Not Found'
                ],401);
            }

            $isPasswordValid = Hash::check($password, $user->password);
            if (!$isPasswordValid) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Password Not Match'
                ],401);
            }

            return response()->json([
                'status' => 'success',
                'data' => $user
            ],200);
        }catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                    'message' => $e->getMessage()
            ],500);
        }
    }

    
}
