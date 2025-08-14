<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $books = Book::with(['author', 'summary', 'category'])
            ->select('books.*')
            ->join('book_summaries', 'books.id', '=', 'book_summaries.book_id')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->orderByDesc('book_summaries.avg_rating')
            ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('books.title', 'LIKE', "%$search%")
                  ->orWhereHas('author', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%$search%");
                  })
                  ->orWhere('categories.name', 'LIKE', "%$search%");
            });
            })
            ->paginate($perPage);
        return view('books.index',[
            'books' => $books,
        ]);
    }
}
