<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    //return top authors with most voters
    public function index()
    {
        $authors = Author::select([
                'authors.id',
                'authors.name',
                DB::raw('SUM(book_summaries.voters_count) as total_voters')
            ])
            ->join('books', 'authors.id', '=', 'books.author_id')
            ->join('book_summaries', 'books.id', '=', 'book_summaries.book_id')
            ->where('book_summaries.avg_rating', '>', 5)
            ->groupBy('authors.id', 'authors.name')
            ->orderByDesc('total_voters')
            ->limit(10)
            ->get();

        return view('top_authors.index', compact('authors'));
    }
}
