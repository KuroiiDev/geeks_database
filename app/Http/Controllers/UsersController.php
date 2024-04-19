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
            ]);
            $data['role'] = 'USER';
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
            $data['password'] = Hash::make($data['password']);
            $isPasswordValid = Hash::check($user->password, $data['password']);
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
            ]);
            $data['role'] = 'STAFF';
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
            ]);
            $data['role'] = 'ADMIN';
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
                'profile' => 'nullable',
            ]);
            if ($request->hasFile('profile')) {
                if($request->file('profile')->isValid()) {
                    try {
                        $file = $request->file('profile');
                        $image = base64_encode(file_get_contents($file));
                        $data['profile'] = $image;
                    }catch (\Throwable $e) {
                        return response()->json(['status'=> 'error Encoding','message'=> $e->getMessage()],500);
                    }
                }
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
