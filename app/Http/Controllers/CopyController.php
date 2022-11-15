<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Copy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CopyController extends Controller
{
    //
    public function index()
    {
        $copies =  Copy::all();
        return $copies;
    }

    public function show($id)
    {
        $copies = Copy::find($id);
        return $copies;
    }
    public function destroy($id)
    {
        Copy::find($id)->delete();
    }
    public function store(Request $request)
    {
        $copy = new Copy();
        $copy->book_id = $request->book_id;
        $copy->hardcovered = $request->hardcovered;
        $copy->publication = $request->publication;
        $copy->status = 0;
        $copy->save();
    }

    public function update(Request $request, $id)
    {
        //a book_id ne változzon! mert akkor már másik példányról van szó
        $copy = Copy::find($id);
        $copy->hardcovered = $request->hardcovered;
        $copy->publication = $request->publication;
        $copy->status = $request->status;
        $copy->save();
    }

    public function copies_pieces($title)
    {
        $copies = Book::with('copy_c')->where('title', '=', $title)->count();
        return $copies;
    }

    //view-k:

    public function newView()
    {
        //új rekord(ok) rögzítése
        $books = Book::all();
        return view('copy.new', ['books' => $books]);
    }

    public function editView($id)
    {
        $books = Book::all();
        $copy = Copy::find($id);
        return view('copy.edit', ['books' => $books, 'copy' => $copy]);
    }

    public function listView()
    {
        $copies = Copy::all();
        //copy mappában list blade
        return view('copy.list', ['copies' => $copies]);
    }
    public function copiesYear($year)
    {
        $answer = DB::table('copies as c')->select(['c.copy_id', "b.title", "b.author"])->join("books as b", "c.book_id", "=", "b.book_id")->where("c.publication", $year)->get();
        return $answer;
    }
    public function cover($hc)
    {
        $answer = DB::table('copies as c')->select(['c.copy_id', "b.title", "b.author"])->join("books as b", "c.book_id", "=", "b.book_id")->where("c.hardcovered", $hc)->get();
        return $answer;
    }
    public function inStore()
    {
        return DB::table('copies as c')->select(['c.copy_id', "b.title", "b.author"])->join("books as b", "c.book_id", "=", "b.book_id")->where("status", "0")->get();
    }
    public function yearAndTitle($year, $title)
    {
        return DB::table('copies as c')->select(['c.copy_id', "b.title", "b.author"])->join("books as b", "c.book_id", "=", "b.book_id")->where("status", "0")->where("publication", $year)->where("title", $title)->get();
    }
    public function test($book_id)
    {
        return Copy::with('lending_c')->where('book_id', $book_id)->get();
    }
}
