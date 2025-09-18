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
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // ページネーション（1ページ15件 = 横3×縦5）
        $books = $query->paginate(15)->appends($request->all());

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
            'title'          => 'required|string|max:255',
            'author'         => 'nullable|string|max:255',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'nullable|integer',
            'price'          => 'nullable|integer',
            'comment'        => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'genres'         => 'nullable|array',
            'rating'         => 'nullable|integer|min:1|max:5', // ★追加
        ]);


        // Bookテーブル用データだけ抽出
        $bookData = $validated;
        unset($bookData['genres']);

        // 画像処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $bookData['image_path'] = $path;
        }

        // Book作成
        $book = Book::create($bookData);

        // ジャンル紐付け
        if (!empty($validated['genres'])) {
            $genreIds = [];
            foreach ($validated['genres'] as $genreName) {
                if (empty($genreName)) continue;
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }
            $book->genres()->sync($genreIds);
        }

        return redirect()->route('books.index')->with('success', '本を登録しました');
    }

    // 詳細表示
    public function show(Book $book)
    {
        // genresはリレーション経由で取得可能
        return view('books.show', compact('book'));
    }

    // 編集フォーム
    public function edit(Book $book)
    {
        $genres = Genre::orderBy('name')->pluck('name')->toArray();
        return view('books.edit', compact('book', 'genres'));
    }

    // 更新処理
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'nullable|string|max:255',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'nullable|integer',
            'price'          => 'nullable|integer',
            'comment'        => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'genres'         => 'nullable|array',
            'genres.*'       => 'nullable|string',
            'rating'         => 'nullable|integer|min:1|max:5',
        ]);

        // Bookテーブル用データだけ抽出（genresは中間テーブル用なので除外）
        $bookData = $validated;
        unset($bookData['genres']);

        // 画像更新
        if ($request->hasFile('image')) {
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            $bookData['image_path'] = $request->file('image')->store('books', 'public');
        }

        // 本データ更新
        $book->update($bookData);

        // ジャンル更新（選択が無ければ空配列でsyncして解除）
        $genreIds = [];
        if (!empty($validated['genres'])) {
            foreach ($validated['genres'] as $genreName) {
                if (empty($genreName)) continue;
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }
        }
        $book->genres()->sync($genreIds);

        // 更新後は一覧に戻す（top）
        return redirect()->route('books.index')->with('success', '本を更新しました');
    }


    // 削除処理
    public function destroy(Book $book)
    {
        if ($book->image_path) {
            Storage::disk('public')->delete($book->image_path);
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', '本を削除しました');
    }
}
