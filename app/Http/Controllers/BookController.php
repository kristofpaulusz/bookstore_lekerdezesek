<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index()
    {
        $books =  Book::all();
        return $books;
    }

    public function show($id)
    {
        $book = Book::find($id);
        return $book;
    }
    public function destroy($id)
    {
        Book::find($id)->delete();
    }
    public function store(Request $request)
    {
        $Book = new Book();
        $Book->author = $request->author;
        $Book->title = $request->title;
        $Book->save();
    }

    public function update(Request $request, $id)
    {
        $Book = Book::find($id);
        $Book->author = $request->author;
        $Book->title = $request->title;
    }

    public function bookCopies($title)
    {
        $copies = Book::with('copy_c')->where('title', '=', $title)->get();
        return $copies;
    }
    public function book_copy_count($title)
    {
        $copies = DB::table('copies as c')
            //->select('mezo_neve')
            ->join('books as b', 'c.book_id', '=', 'b.book_id')
            ->where('b.title', '=', $title)
            ->count();
        return $copies;
    }
}
