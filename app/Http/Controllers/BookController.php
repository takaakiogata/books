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
        // ジャンル一覧 
        $genres = Genre::orderBy('name')->get();
        $user = auth()->user();

        // --- おすすめフィルタの場合 ---
        if ($request->filled('filter') && $request->filter === 'recommended' && $user) {
            // 自分のよく読むジャンル上位3つ 
            $favoriteGenreIds = $user->favorites()->with('genres')
                ->get()->pluck('genres.*.id')
                ->flatten()->countBy()->sortDesc()->keys()->take(3);

            if ($favoriteGenreIds->isNotEmpty()) {
                // 自分の登録済みタイトル 
                $userBookTitles = $user->books()->pluck('title')->toArray();

                // おすすめクエリ
                $query = Book::with('genres')
                    ->whereHas('genres', function ($q) use ($favoriteGenreIds) {
                        $q->whereIn('id', $favoriteGenreIds);
                    })
                    ->where('user_id', '!=', $user->id)
                    ->whereNotIn('title', $userBookTitles)
                    ->where('rating', '>=', 4) // 評価4以上
                    ->orderBy('rating', 'desc');
            } else {
                $query = Book::whereRaw('0=1'); // 空
            }

            $favoriteBookIds = $user->favorites()->pluck('book_id')->toArray();
        } else {
            // --- 通常の本一覧 ---
            $query = Book::with('genres')->where('user_id', $user->id);

            // お気に入りフィルター 
            $favoriteBookIds = [];
            if (auth()->check()) {
                $favoriteBookIds = $user->favorites()->pluck('book_id')->toArray();
                if ($request->filled('filter') && $request->filter === 'favorites') {
                    $query->whereIn('id', $favoriteBookIds);
                }
            }

            // 検索フィルタ
            if ($request->filled('keyword')) {
                $query->where('title', 'like', '%' . $request->keyword . '%');
            }
            if ($request->filled('author')) {
                $query->where('author', 'like', '%' . $request->author . '%');
            }
            if ($request->filled('publisher')) {
                $query->where('publisher', 'like', '%' . $request->publisher . '%');
            }
            if ($request->filled('published_year')) {
                $query->where('published_year', $request->published_year);
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
        }

        // --- ソート処理 ---
        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedFields = ['created_at', 'rating', 'price', 'title'];
        $allowedOrders = ['asc', 'desc'];
        if (!in_array($sortField, $allowedFields)) $sortField = 'created_at';
        if (!in_array($sortOrder, $allowedOrders)) $sortOrder = 'desc';
        $query->orderBy($sortField, $sortOrder);

        // ページネーション
        $books = $query->paginate(12)->appends($request->all());

        return view('books.top', compact('books', 'genres', 'favoriteBookIds', 'sortField', 'sortOrder'));
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
        $rules = [
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'publisher'      => 'required|string|max:255',
            'published_year' => ['required', 'regex:/^\d{4}$/'], // 4桁必須
            'price'          => 'required|integer|min:0',
            'comment'        => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'genres'         => 'required|array|min:1|max:3',
            'genres.*'       => 'nullable|string',
            'rating'         => 'required|integer|min:1|max:5',
        ];

        $messages = [
            'title.required'          => 'タイトルは必須です。',
            'author.required'         => '著者は必須です。',
            'publisher.required'      => '出版社は必須です。',
            'published_year.required' => '発売年は必須です。',
            'published_year.regex'    => '発売年は4桁の数字で入力してください。',
            'price.required'          => '価格は必須です。',
            'price.integer'           => '価格は整数で入力してください。',
            'genres.required'         => 'ジャンルを1つ以上選択してください。',
            'rating.required'         => '評価は必須です。',
            'rating.integer'          => '評価は整数で入力してください。',
        ];

        $validated = $request->validate($rules, $messages);

        $bookData = $validated;
        unset($bookData['genres']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $bookData['image_path'] = $path;
        }

        $book = new Book($bookData);
        $book->user_id = auth()->id();
        $book->save();

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
        return view('books.show', compact('book'));
    }

    // 編集フォーム
    public function edit(Book $book)
    {
        if ($book->user_id !== auth()->id()) {
            abort(403, 'この本の編集権限がありません。');
        }

        $genres = Genre::orderBy('name')->pluck('name')->toArray();
        return view('books.edit', compact('book', 'genres'));
    }

    // 更新処理
    public function update(Request $request, Book $book)
    {
        if ($book->user_id !== auth()->id()) {
            abort(403, 'この本の更新権限がありません。');
        }

        $rules = [
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'publisher'      => 'required|string|max:255',
            'published_year' => ['required', 'regex:/^\d{4}$/'],
            'price'          => 'required|integer|min:0',
            'genres'         => 'required|array|min:1|max:3',
            'genres.*'       => 'nullable|string',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:1000',
            'image'          => 'nullable|image|max:2048',
        ];

        $messages = [
            'title.required'          => 'タイトルは必須です。',
            'author.required'         => '著者は必須です。',
            'publisher.required'      => '出版社は必須です。',
            'published_year.required' => '発売年は必須です。',
            'published_year.regex'    => '発売年は4桁の数字で入力してください。',
            'price.required'          => '価格は必須です。',
            'price.integer'           => '価格は整数で入力してください。',
            'genres.required'         => 'ジャンルを1つ以上選択してください。',
            'rating.required'         => '評価は必須です。',
            'rating.integer'          => '評価は整数で入力してください。',
        ];

        $validated = $request->validate($rules, $messages);

        $bookData = $validated;
        unset($bookData['genres']);

        if ($request->hasFile('image')) {
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            $bookData['image_path'] = $request->file('image')->store('books', 'public');
        }

        $book->update($bookData);

        $genreIds = [];
        if (!empty($validated['genres'])) {
            foreach ($validated['genres'] as $genreName) {
                if (empty($genreName)) continue;
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }
        }
        $book->genres()->sync($genreIds);

        return redirect()->route('books.index')->with('success', '本を更新しました');
    }

    // 削除処理
    public function destroy(Book $book)
    {
        if ($book->user_id !== auth()->id()) {
            abort(403, 'この本の削除権限がありません。');
        }

        if ($book->image_path) {
            Storage::disk('public')->delete($book->image_path);
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', '本を削除しました');
    }
}
