<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // 年・月・表示モード
        $selectedYear = $request->input('year', now()->year);
        $selectedMonth = $request->input('month', now()->month);
        $mode = $request->input('mode', 'yearly');

        // 選択可能な年リスト
        $years = range(2023, now()->year);

        // 上位著者TOP3
        $topAuthors = Book::where('user_id', $userId)
            ->select('author', DB::raw('COUNT(*) as count'))
            ->groupBy('author')
            ->orderByDesc('count')
            ->take(3)
            ->get();

        // ジャンル割合
        $genreChart = Book::where('user_id', $userId)
            ->join('book_genre', 'books.id', '=', 'book_genre.book_id')
            ->join('genres', 'book_genre.genre_id', '=', 'genres.id')
            ->select('genres.name', DB::raw('COUNT(*) as count'))
            ->groupBy('genres.name')
            ->get();
        $genreCounts = $genreChart->pluck('count', 'name')->sortDesc();

        // 価格集計
        $priceSummary = Book::where('user_id', $userId)
            ->select(
                DB::raw('COUNT(*) as book_count'),
                DB::raw('SUM(price) as total_price'),
                DB::raw('AVG(price) as avg_price')
            )
            ->first();

        // 年間データ（ゼロ埋め）
        $rawYearly = Book::where('user_id', $userId)
            ->whereYear('created_at', $selectedYear)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->pluck('count', 'month');
        $yearlyBooks = collect(range(1, 12))->map(function ($m) use ($rawYearly) {
            return [
                'month' => $m,
                'count' => $rawYearly[$m] ?? 0,
            ];
        });

        // 月間データ（ゼロ埋め、単日冊数に変換）
        $rawMonthly = Book::where('user_id', $userId)
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->select(DB::raw('DAY(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->pluck('count', 'day');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
        $cumulative = 0;
        $dailyBooksCumulative = collect(range(1, $daysInMonth))->map(function ($d) use ($rawMonthly, &$cumulative) {
            $cumulative += $rawMonthly[$d] ?? 0;
            return $cumulative;
        });

        return view('mypage.index', [
            'topAuthors' => $topAuthors,
            'genreCounts' => $genreCounts,
            'priceSummary' => $priceSummary,
            'yearlyBooks' => $yearlyBooks,
            'dailyBooks' => $dailyBooksCumulative,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'mode' => $mode
        ]);
    }
}
