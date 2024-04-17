<?php

namespace App\Http\Controllers;

use App\Models\Bookmarks;
use Exception;
use Illuminate\Http\Request;

class BookmarksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $data = Bookmarks::where('user_id',$id)->with(['user', 'book'])->get();
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
        try{
            $data = $request->validate([
                'book_id' => 'required',
                'user_id' => 'required'
            ]);
            $validate = Bookmarks::where('user_id',$data['user_id'])->where('book_id',$data['book_id'])->get();
            if ($validate->count() > 0){
                return response()->json(['status' => 'error', 'message' => 'Already in Bookmark!'], 203);
            }
            $bookmark = Bookmarks::create($data);
            return response()->json([
                'status' => 'success',
                'data' => $bookmark
            ],201);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }

    public function check(Request $request)
    {
        try{
            $data = $request->validate([
                'book_id' => 'required',
                'user_id' => 'required'
            ]);
            $validate = Bookmarks::where('user_id',$data['user_id'])->where('book_id',$data['book_id'])->first();
            if ($validate){
                return response()->json(['status' => 'found', 'id' => $validate['id']], 200);
            }
            return response()->json(['status' => 'success'],203);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookmarks $bookmarks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookmarks $bookmarks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bookmarks $bookmarks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $data = Bookmarks::where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }
}
