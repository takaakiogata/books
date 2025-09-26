<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Book $book)
    {
        $user = auth()->user();

        if ($user->favorites()->where('book_id', $book->id)->exists()) {
            $user->favorites()->detach($book->id);
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->attach($book->id);
            return response()->json(['status' => 'added']);
        }
    }
}
