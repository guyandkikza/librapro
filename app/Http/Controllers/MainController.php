<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class MainController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->query('search');

    if ($search) {
      $books = Book::select('title', 'description', 'author', 'code', 'image')->when($search, function ($query, $search) {
          return $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('author', 'like', '%' . $search . '%');
      })->paginate(100);
    } else {
      $books = Book::select('title', 'description', 'author', 'code', 'image')->paginate(100);
    }
    return view('welcome', compact('books'));
  }
}