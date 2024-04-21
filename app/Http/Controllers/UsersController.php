<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    
    public function indexUser()
    {
        try
        {
            $data = Users::orderBy('id', 'ASC')->where('role', '=', 'USER')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'=> 'failed',
                'message'=> $e->getMessage()
            ],500);
        }
    }

    public function userId($id)
    {
        try
        {
            $data = Users::orderBy('id', 'ASC')->where('id', '=', $id)->first();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'=> 'failed',
                'message'=> $e->getMessage()
            ],500);
        }
    }

    public function indexStaff()
    {
        try
        {
            $data = Users::orderBy('id', 'ASC')->where('role', '=', 'STAFF')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'=> 'failed',
                'message'=> $e->getMessage()
            ],500);
        }
    }
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
                'role' => 'nullable',
                'profile' => 'nullable',
            ]);
            $data['role'] = 'USER';
            $data['profile'] = "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";
            $data['password'] = Hash::make($data['password']);
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

    public function staffRegister(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'role' => 'nullable',
                'profile' => 'nullable',
            ]);
            $data['role'] = 'STAFF';
            $data['profile'] = "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";
            $data['password'] = Hash::make($data['password']);
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

    public function staffLogin(Request $request)
    {
        try {
            $data = $request->validate([
                'email'=>'required',
                'password'=>'required'
            ]);

            $user = Users::with([])
                ->where('email', '=', $data['email'])
                ->where('role', '!=', 'USER')
                ->first();
            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Account Not Found'
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
    public function adminRegister(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'role' => 'nullable',
                'profile' => 'nullable',
            ]);
            $data['role'] = 'ADMIN';
            $data['profile'] = "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";
            $data['password'] = Hash::make($data['password']);
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

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'string',
                'password' => 'string',
                'profile' => 'string',
            ]);
            if ($data['profile'] == 'NONE') {
                $prof = Users::where('id', $id)->first();
                $data['profile'] == $prof['profile'];
            }
            Users::where('id', $id)->update($data);
            $update = Users::where('id', $id)->first();
            return response()->json([
                'status' => 'success',
                'data' => $update,
                'pass' => $update['password']
            ],201);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }
    
}
