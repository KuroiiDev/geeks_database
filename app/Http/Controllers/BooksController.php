<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Exception;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Books::orderBy('created_at', 'DESC')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    public function orderAtoZ()
    {
        $data = Books::orderBy('title', 'ASC')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    public function topRent()
    {
        $data = Books::orderBy('rented', 'DESC')->first();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required',
                'writer' => 'required',
                'publisher' => 'required',
                'synopsis' => 'required',
                'publish_year' => 'required',
            ]);
            $add = Books::create($data);
            return response()->json(['status'=> 'success','data'=> $add],201);
        } catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function byID($id)
    {
        try {
            $data = Books::with([])->where('id', '=', $id)->first();
            if (!$data) {
                return response()->json(['status'=>'id not found'],404);
            }
            /*
            if ($this->request->method() === 'POST') {
                return $this->patch($data);
            }
            */
            return response()->json([
                'status'=>'success',
                'data'=> $data
            ],200);
        } catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'title' => 'string',
                'writer' => 'string',
                'publisher' => 'string',
                'synopsis' => 'string',
                'publish_year' => 'integer',
            ]);
            Books::where('id', $id)->update($data);
            $update = Books::where('id', '=', $id)->first();
            return response()->json([
                'status' => 'success',
                'data' => $update
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }
}
