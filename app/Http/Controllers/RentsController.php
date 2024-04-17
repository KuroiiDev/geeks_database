<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Rents;
use App\Models\Users;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class RentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Rents::orderBy('created_at', 'DESC')->get();
        if ($data->count() <=0) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    public function byId($id)
    {
        try {
            $data = Rents::where('id', $id)->with(['book','user'])->first();
            if (!$data) {
            return response()->json(['status'=>'not found'],404);
            }
            return response()->json([
                'status'=>'success',
                'data'=> $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }   

    public function current($id)
    {
        try {
            $data = Rents::where('user_id', $id)->where('status', ['BOOKED', 'RENTED'])->with(['book','user'])->orderBy('created_at', 'DESC')->first();
            if (!$data) {
            return response()->json(['status'=>'not found'],404);
            }
            return response()->json([
                'status'=>'success',
                'data'=> $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function byUser($id)
    {
        try {
            $data = Rents::where('user_id', $id)->with(['book','user'])->orderBy('created_at', 'DESC')->get();
            if (!$data) {
            return response()->json(['status'=>'not found'],404);
            }
            return response()->json([
                'status'=>'success',
                'data'=> $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
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

            } else if(Rents::where('user_id', $data['user_id'])->where('status', ['BOOKED', 'RENTED'])->first()){

                return response()->json(['status'=> 'error','message'=> 'User Can Only Rent 1 Book Each Time!'],203);

            }else {
                $rent = Rents::create($data);
                $rented = ['rented'=> $book['rented']+1, 'status'=>'UNAVAILABLE'];
                Books::where('id', $data['book_id'])->update($rented);
                return response()->json([
                    'status'=> 'success',
                    'data'=> [
                        'rent' => $rent,
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
            if (Rents::where('id', $id)->first()['status'] != 'RETURNED') {

                Rents::where('id', $id)->update(['status' => 'RETURNED']);
                $rent = Rents::where('id', $id)->with(['book','user'])->first();
                Books::where('id', $rent['book_id'])->update(['status'=>'AVAILABLE']);
                return response()->json(['status'=> 'success','data'=> $rent],201);
            } else {
                return response()->json(['status'=> 'error','message'=> 'Already returned'],400);
            }
            
        }catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function verifyRent($id){
        try {
            if (Rents::where('id', $id)->first()['status'] != 'RETURNED' && Rents::where('id', $id)->first()['status'] != 'RENTED') {

                $today = Carbon::now()->format('Y-m-d');
                Rents::where('id', $id)->update(['status' => 'RENTED', 'rent_date'=> $today]);
                $rent = Rents::where('id', $id)->with(['book', 'user'])->first();
                return response()->json(['status'=> 'success','data'=> $rent],201);
            } else {
                return response()->json(['status'=> 'error','message'=> 'Already returned Or Rented'],400);
            }
            
        }catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }
}
