<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Rents;
use App\Models\Users;
use Illuminate\Http\Request;

class RentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Rents::orderBy('created_at', 'DESC')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function requestRent(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required',
                'book_id' => 'required',
                'return_date' => 'required',
            ]);
            $book = Books::with([])->where('id', '=', $data['book_id'])->first();
            if ($book['status'] == "UNAVAILABLE"){
                return response()->json(['status'=> 'error','message'=> 'Book Unavailable!'],400);
            } else{
                $add = Rents::create($data);
                $rented = ['rented'=> $book['rented']+1, 'status'=>'UNAVAILABLE'];
                Books::where('id', $data['book_id'])->update($rented);
                return response()->json([
                    'status'=> 'success',
                    'data'=> [
                        'book' => Books::with([])->where('id', '=', $data['book_id'])->first(),
                        'user' => Users::with([])->where('id', '=', $data['user_id'])->first()
                    ]
                ],201);
            }
        } catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function returnRent($id){
        try {
            Rents::where('id', $id)->update(['status' => 'RETURNED']);
            $rent = Rents::with([])->where('id', $id)->first();
            Books::where('id', $rent['book_id'])->update(['status'=>'AVAILABLE']);
            return response()->json(['status'=> 'success','data'=> $rent],201);
        }catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }
}
