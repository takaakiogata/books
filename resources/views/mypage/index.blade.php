<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-green-800 leading-tight">マイページ</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-8 space-y-6">

                {{-- 1. 上位3著者 --}}
                <div>
                    <h3 class="text-xl font-bold mb-2">登録本の多い著者トップ3</h3>
                    <ul class="list-disc pl-5">
                        @foreach($topAuthors as $author)
                        <li>{{ $author->author }}（{{ $author->count }}冊）</li>
                        @endforeach
                        @if($topAuthors->isEmpty())
                        <li>まだ本が登録されていません。</li>
                        @endif
                    </ul>
                </div>

                {{-- 2. ジャンル円グラフ --}}
                <div>
                    <h3 class="text-xl font-bold mb-2">ジャンル割合</h3>
                    <canvas id="genreChart" class="w-1/3 h-32 mx-auto"></canvas>

                </div>

                {{-- 3. 価格集計 --}}
                <div>
                    <h3 class="text-xl font-bold mb-2">購入本の価格まとめ</h3>
                    <p>本の総数: {{ $priceSummary->book_count ?? 0 }}冊</p>
                    <p>合計金額: ¥{{ number_format($priceSummary->total_price ?? 0) }}</p>
                    <p>平均価格: ¥{{ number_format($priceSummary->avg_price ?? 0) }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('genreChart').getContext('2d');
        const data = {
            labels: {
                !!json_encode($genreChart - > pluck('name')) !!
            },
            datasets: [{
                data: {
                    !!json_encode($genreChart - > pluck('count')) !!
                },
                backgroundColor: ['#34D399', '#60A5FA', '#FBBF24', '#F87171', '#A78BFA'],
            }]
        };
        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false // これを入れると親 div のサイズに合わせて描画
            }
        });
    </script>
</x-app-layout>