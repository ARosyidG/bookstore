<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\BookSummary;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function create()
    {
        // dump("Loading authors for rating form");
        $authors = Author::get(['id', 'name']);
        return view('ratings.index', compact('authors'));
    }

    public function booksByAuthor(Author $author)
    {
        // error_log('Some message here.');
        // dump("Fetching books for author:");
        return response()->json(
            $author->books()->orderBy('title')->get(['id','title'])
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_id' => 'required|integer|exists:authors,id',
            'book_id'   => 'required|integer|exists:books,id',
            'rating'    => 'required|integer|between:1,10',
        ]);

        DB::transaction(function () use ($validated) {
            Rating::create([
                'book_id' => $validated['book_id'],
                'rating'  => $validated['rating'],
            ]);

            // Recompute summary (avg + voters) for this book
            $aggregate = Rating::where('book_id', $validated['book_id'])
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as voters_count')
                ->first();

            BookSummary::updateOrCreate(
                ['book_id' => $validated['book_id']],
                [
                    'avg_rating'   => round((float) $aggregate->avg_rating, 2),
                    'voters_count' => (int) $aggregate->voters_count,
                ]
            );
        });

        return redirect()->route('books.index')->with('status', 'Rating submitted successfully.');
    }

}
