<?php

namespace App\Http\Controllers;

use App\Models\Ratings;
use Exception;
use Illuminate\Http\Request;

class RatingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Ratings::with(['users', 'books'])->orderBy('created_at', 'DESC')->get();
            if (!$data) {
                return response()->json(['status' => 'not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => "Internal server error",
                'message' => $e
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required',
                'book_id' => 'required',
                'review' => 'required',
                'rating' => 'required',
            ]);
            $rating = Ratings::create($data);
            return response()->json([
                'status' => 'success',
                'data' => $rating
            ],201);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $data = Ratings::all()->find($id);
            if (!$data) {
                return response()->json(['status' => 'not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ratings $ratings)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ratings $ratings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ratings $ratings)
    {
        //
    }
}
