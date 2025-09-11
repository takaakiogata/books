<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // 本一覧
    public function index(Request $request)
    {
        $genres = Genre::orderBy('name')->get();

        $query = Book::with('genres');

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }
        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('id', $request->genre);
            });
        }

        $books = $query->get();

        return view('books.top', compact('books', 'genres'));
    }

    // 新規登録フォーム
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('books.create', compact('genres'));
    }

    // 新規登録処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'published_year' => 'required|integer',
            'price' => 'required|integer',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'genres' => 'nullable|array',
        ]);

        // 画像アップロード
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $path;
        }

        // Book作成
        $book = Book::create($validated);

        // ジャンル紐付け（文字列をIDに変換しながら自動作成）
        if (!empty($validated['genres'])) {
            $genreIds = [];
            foreach ($validated['genres'] as $genreName) {
                if (empty($genreName)) continue; // 選択されていない場合はスキップ
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }
            $book->genres()->sync($genreIds);
        }


        return redirect()->route('books.index')->with('success', '本を登録しました');
    }
}
