<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Genre;

class MypageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. 上位3著者
        $topAuthors = Book::where('user_id', $userId)
            ->select('author', DB::raw('COUNT(*) as count'))
            ->groupBy('author')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        // 2. ジャンル集計
        $genreCounts = Genre::select('name')
            ->withCount(['books' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->having('books_count', '>', 0)
            ->get();

        // 円グラフ用データ
        $genreChart = $genreCounts->map(function($genre){
            return ['name' => $genre->name, 'count' => $genre->books_count];
        });

        // 3. 購入本の合計・平均
        $priceSummary = Book::where('user_id', $userId)
            ->selectRaw('SUM(price) as total_price, AVG(price) as avg_price, COUNT(*) as book_count')
            ->first();

        return view('mypage.index', compact('topAuthors', 'genreChart', 'priceSummary'));
    }
}
