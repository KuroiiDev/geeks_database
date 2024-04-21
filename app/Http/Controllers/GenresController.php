<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use App\Models\Genres_Relation;
use Exception;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Genres::orderBy('genre', 'ASC')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],203);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    public function indexId()
    {
        $data = Genres::orderBy('id')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],203);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try 
        {
            $data = $request->validate(['genre' => 'required']);
            $add = Genres::create($data);
            return response()->json(['status'=> 'success','data'=> $add],201);
        } catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Genres $genres)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genres $genres)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genres $genres)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            Genres_Relation::where('genre_id',$id)->delete();
            $data = Genres::where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }
}
