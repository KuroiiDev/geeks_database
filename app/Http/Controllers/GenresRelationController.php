<?php

namespace App\Http\Controllers;

use App\Models\Genres_Relation;
use Exception;
use Illuminate\Http\Request;

class GenresRelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Genres_Relation::orderBy('id', 'DESC')->get();
            if ($data->count() <=0) {
                return response()->json(['status' => 'empty data'], 203);
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function book($id)
    {
        try {
            $data = Genres_Relation::where('book_id',$id)->with(['genre', 'book'])->get();
            if ($data->count() <=0) {
                return response()->json(['status' => 'not found'], 203);
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function genre($id)
    {
        try {
            $data = Genres_Relation::where('genre_id',$id)->with(['genre', 'book'])->get();
            if ($data->count() <=0) {
                return response()->json(['status' => 'not found'], 203);
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                'book_id' => 'required',
                'genre_id' => 'required'
            ]);
            $validate = Genres_Relation::where('genre_id',$data['genre_id'])->where('book_id',$data['book_id'])->get();
            if ($validate->count() > 0){
                return response()->json(['status' => 'error', 'message' => 'Already Linked!'], 203);
            }
            $bookmark = Genres_Relation::create($data);
            return response()->json([
                'status' => 'success',
                'data' => $bookmark
            ],201);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Genres_Relation $genres_Relation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genres_Relation $genres_Relation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genres_Relation $genres_Relation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $data = Genres_Relation::where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }
}
