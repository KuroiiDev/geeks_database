<?php

namespace App\Http\Controllers;

use App\Models\Bookmarks;
use App\Models\Books;
use App\Models\Genres_Relation;
use App\Models\Ratings;
use App\Models\Rents;
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
            return response()->json(['status'=>'not found'],203);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }


    public function indexId()
    {
        $data = Books::orderBy('id')->get();
        if (!$data) {
            return response()->json(['status'=>'not found'],203);
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
            return response()->json(['status'=>'not found'],203);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    public function topRent()
    {
        $data = Books::orderBy('rented', 'DESC')->first();
        updateRating($data['id']);
        if (!$data) {
            return response()->json(['status'=>'not found'],404);
        }
        return response()->json([
            'status'=>'success',
            'data'=> $data
        ],200);
    }

    public function topRated()
    {
        $data = Books::orderBy('rating', 'DESC')->get();
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
                'cover' => 'nullable'
            ]);
            if ($request->hasFile('cover')) {
                if($request->file('cover')->isValid()) {
                    try {
                        $file = $request->file('cover');
                        $image = base64_encode(file_get_contents($file));
                        $data['cover'] = $image;
                    }catch (\Throwable $e) {
                        return response()->json(['status'=> 'error Encoding','message'=> $e->getMessage()],500);
                    }
                }
            } else if ($data['cover'] == null) {
                $data['cover'] = "iVBORw0KGgoAAAANSUhEUgAAASwAAAGQCAYAAAAUdV17AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAEdWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4KPHg6eG1wbWV0YSB4bWxuczp4PSdhZG9iZTpuczptZXRhLyc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczpBdHRyaWI9J2h0dHA6Ly9ucy5hdHRyaWJ1dGlvbi5jb20vYWRzLzEuMC8nPgogIDxBdHRyaWI6QWRzPgogICA8cmRmOlNlcT4KICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0nUmVzb3VyY2UnPgogICAgIDxBdHRyaWI6Q3JlYXRlZD4yMDI0LTAzLTA0PC9BdHRyaWI6Q3JlYXRlZD4KICAgICA8QXR0cmliOkV4dElkPjUyZmQwYjRlLTI0YWMtNDA1ZC1hYzRkLTcyNzM4MDcyODMzMjwvQXR0cmliOkV4dElkPgogICAgIDxBdHRyaWI6RmJJZD41MjUyNjU5MTQxNzk1ODA8L0F0dHJpYjpGYklkPgogICAgIDxBdHRyaWI6VG91Y2hUeXBlPjI8L0F0dHJpYjpUb3VjaFR5cGU+CiAgICA8L3JkZjpsaT4KICAgPC9yZGY6U2VxPgogIDwvQXR0cmliOkFkcz4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJz4KICA8ZGM6dGl0bGU+CiAgIDxyZGY6QWx0PgogICAgPHJkZjpsaSB4bWw6bGFuZz0neC1kZWZhdWx0Jz5VbnRpdGxlZCBkZXNpZ24gLSAxPC9yZGY6bGk+CiAgIDwvcmRmOkFsdD4KICA8L2RjOnRpdGxlPgogPC9yZGY6RGVzY3JpcHRpb24+CgogPHJkZjpEZXNjcmlwdGlv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";
            }
            $add = Books::create($data);
            return response()->json(['status'=> 'success','data'=> $add],201);
        } catch (\Throwable $e) {
            return response()->json(['status'=> 'error','message'=> $e->getMessage()],500);
        }
    }

    public function byID($id)
    {
        try {
            updateRating($id);
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
            if ($request->hasFile('cover')) {
                if($request->file('cover')->isValid()) {
                    try {
                        $file = $request->file('cover');
                        $image = base64_encode(file_get_contents($file));
                        $data['cover'] = $image;
                    }catch (\Throwable $e) {
                        return response()->json(['status'=> 'error Encoding','message'=> $e->getMessage()],500);
                    }
                }
            }
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

    public function destroy($id)
    {
        try{
            Genres_Relation::where('book_id',$id)->delete();
            Bookmarks::where('book_id',$id)->delete();
            Rents::where('book_id',$id)->delete();
            Ratings::where('book_id',$id)->delete();

            $data = Books::where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error','message'=> $e],500);
        }
    }
}

function updateRating($id)
    {
        try
        {
            $count = Ratings::where('book_id', $id)->get()->count();
            if ($count != 0){
                $total = Ratings::where('book_id', $id)->sum('rating');
                $rating = $total / $count;
                Books::where('id', $id)->update(['rating' => $rating]);
            }
            //print($rating);
        } catch (Exception $e) {
            //print($e);
        }
    }
