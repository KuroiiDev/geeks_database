<?php

namespace App\Http\Controllers;

use App\Models\Books;
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
            $data = Ratings::with(['user', 'book'])->orderBy('created_at', 'DESC')->get();
            if ($data->count() <=0) {
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
                'rating' => 'required',
            ]);
            $check =  Ratings::where('user_id',$data['user_id'])->where('book_id',$data['book_id'])->first();
            if ($check) {
                $rating = Ratings::where('id', $check['id'])->update($data);
                updateRating($data['book_id']);
                $check =  Ratings::where('user_id',$data['user_id'])->where('book_id',$data['book_id'])->first();
                return response()->json([
                    'status' => 'success Updated',
                    'data' => $check
                ],201);
            }
            $rating = Ratings::create($data);
            updateRating($data['book_id']);
            return response()->json([
                'status' => 'success',
                'data' => $rating
            ],201);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function book($id)
    {
        try{
            updateRating($id);
            $data = Ratings::with(['user', 'book'])->where('book_id',$id)->get();
            if (!$data) {
                return response()->json(['status' => 'not found'], 203);
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
    public function rating($id)
    {
        try{
            $data = Ratings::with(['user', 'book'])->where('id',$id)->get();
            if (!$data) {
                return response()->json(['status' => 'not found'], 203);
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
function updateRating($id)
{
    try
    {
        $count = Ratings::where('book_id', $id)->get()->count();
        $total = Ratings::where('book_id', $id)->sum('rating');
        $rating = $total / $count;
        Books::where('id', $id)->update(['rating' => $rating]);
        //print($rating);
    } catch (Exception $e) {
        //print($e);
    }
}