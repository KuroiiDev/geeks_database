<?php

namespace App\Http\Controllers;

use App\Models\Books;
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
/*
    public function getByID($id)
    {
        try {
            $data = Books::with([])->where('id', '=', $id)->first();
            if (!$data) {
                return $this->jsonNotFoundResponse(' not found');
            }
            if ($this->request->method() === 'POST') {
                return $this->patch($data);
            }
            return $this->jsonSuccessResponse('success', $data);
        } catch (\Throwable $e) {
            return $this->jsonErrorResponse('internal server error ' . $e->getMessage());
        }
    }
*/
}
