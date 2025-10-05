<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-green-800 leading-tight flex items-center gap-2">
                📊 マイページ
            </h2>
            <a href="{{ route('books.index') }}"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                本一覧へ戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 上位3著者 --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold mb-4 text-green-800">📖 登録本の多い著者トップ3</h3>
                <ul class="list-disc pl-6 space-y-1 text-gray-700">
                    @forelse($topAuthors as $author)
                        <li>{{ $author->author }}（{{ $author->count }}冊）</li>
                    @empty
                        <li>まだ本が登録されていません。</li>
                    @endforelse
                </ul>
            </div>

            {{-- ジャンル円グラフ --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold mb-4 text-green-800">📊 ジャンル割合</h3>
                <div class="w-full max-w-md mx-auto">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>

            {{-- 価格集計 --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold mb-4 text-green-800">💰 購入本の価格まとめ</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-gray-700">
                    <p><span class="font-semibold">本の総数:</span> {{ $priceSummary->book_count ?? 0 }}冊</p>
                    <p><span class="font-semibold">合計金額:</span> ¥{{ number_format($priceSummary->total_price ?? 0) }}</p>
                    <p><span class="font-semibold">平均価格:</span> ¥{{ number_format($priceSummary->avg_price ?? 0) }}</p>
                </div>
            </div>

            {{-- 読書数グラフ --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold mb-6 text-green-800">📈 読書数の推移（年間）</h3>

                {{-- タブ切替 --}}
                <div class="flex space-x-4 mb-6 border-b">
                    <button type="button" onclick="switchTab('yearly')" id="tab-yearly"
                        class="px-4 py-2 border-b-2 font-semibold">年間</button>
                    {{--
                    <button type="button" onclick="switchTab('monthly')" id="tab-monthly"
                        class="px-4 py-2 border-b-2 font-semibold">月間</button>
                    --}}
                </div>

                {{-- 年間選択 --}}
                <form id="yearly-form" method="GET" action="{{ route('mypage.index') }}" class="mb-4">
                    <label for="year" class="mr-2 font-semibold text-gray-700">対象年:</label>
                    <select name="year" id="year" onchange="this.form.submit()"
                        class="border rounded px-2 py-1 focus:ring-2 focus:ring-green-500">
                        @foreach($years as $year)
                            <option value="{{ $year }}" @selected($year == $selectedYear)>{{ $year }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="mode" value="yearly">
                </form>

                {{--
                月間選択フォームはコメントアウト
                <form id="monthly-form" method="GET" action="{{ route('mypage.index') }}" class="mb-4 hidden">
                    ...
                </form>
                --}}

                {{-- グラフ --}}
                <div class="w-full h-96">
                    <canvas id="readingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ランダムカラー生成
        function generateColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = Math.floor((360 / count) * i);
                colors.push(`hsl(${hue}, 70%, 60%)`);
            }
            return colors;
        }

        // ジャンル円グラフ
        const genreLabels = {!! json_encode($genreCounts->keys()) !!};
        const genreData = {!! json_encode($genreCounts->values()) !!};
        new Chart(document.getElementById('genreChart'), {
            type: 'pie',
            data: {
                labels: genreLabels,
                datasets: [{
                    data: genreData,
                    backgroundColor: generateColors(genreLabels.length),
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 年間データ（棒グラフ）
        const yearlyLabels = {!! json_encode($yearlyBooks->pluck('month')->map(fn($m)=> $m.'月')) !!};
        const yearlyCounts = {!! json_encode($yearlyBooks->pluck('count')) !!};
        const yearlyData = {
            labels: yearlyLabels,
            datasets: [{
                label: '月ごとの読書冊数',
                data: yearlyCounts,
                backgroundColor: '#34D399'
            }]
        };

        const ctx = document.getElementById('readingChart').getContext('2d');
        let readingChart = new Chart(ctx, {
            type: 'bar',
            data: yearlyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 50, ticks: { stepSize: 5 } }
                }
            }
        });

        // タブ切替（今は年間のみ表示）
        function switchTab(mode) {
            if (mode === 'yearly') {
                document.getElementById('yearly-form').classList.remove('hidden');
                // document.getElementById('monthly-form').classList.add('hidden');
                document.getElementById('tab-yearly').classList.add('border-green-600');
                // document.getElementById('tab-monthly').classList.remove('border-green-600');

                readingChart.config.type = 'bar';
                readingChart.config.data = yearlyData;
                readingChart.options.scales.y.beginAtZero = true;
                readingChart.options.scales.y.max = 50;
            }
        }

        const currentMode = "yearly";
        switchTab(currentMode);
    </script>
</x-app-layout>
