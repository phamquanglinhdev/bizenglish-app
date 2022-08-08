<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function show($slug)
    {
        $book=Book::where("slug","=",$slug)->get();
        if($book->count()==0)
            return view("errors.404");
        else
        return view("show-book",["book"=>$book->first()]);
    }
}
