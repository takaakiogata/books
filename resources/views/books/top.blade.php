<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-green-800 leading-tight flex items-center gap-2">
                📚 本一覧
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('books.create') }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                    新規登録
                </a>
                <a href="{{ route('mypage.index') }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                    マイページ
                </a>
                {{-- ログアウト --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition">
                        ログアウト
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-8">

                {{-- 検索フォーム --}}
                <form method="GET" action="{{ route('books.index') }}"
                    class="flex flex-wrap gap-4 mb-8 p-6 bg-green-50 rounded-xl border border-green-100 shadow-sm">

                    <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">

                    {{-- タイトル --}}
                    <div class="flex flex-col flex-1 min-w-[160px]">
                        <label for="keyword" class="text-sm text-gray-600 mb-1">タイトル</label>
                        <input id="keyword" type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="タイトルで検索"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    {{-- 著者 --}}
                    <div class="flex flex-col min-w-[140px]">
                        <label for="author" class="text-sm text-gray-600 mb-1">著者名</label>
                        <input id="author" type="text" name="author" value="{{ request('author') }}"
                            placeholder="著者名"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    {{-- 出版社 --}}
                    <div class="flex flex-col min-w-[160px]">
                        <label for="publisher" class="text-sm text-gray-600 mb-1">出版社</label>
                        <input id="publisher" type="text" name="publisher" value="{{ request('publisher') }}"
                            placeholder="出版社名"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    {{-- 発売年 --}}
                    <div class="flex flex-col w-32">
                        <label for="published_year" class="text-sm text-gray-600 mb-1">発売年</label>
                        <input id="published_year" type="number" name="published_year" value="{{ request('published_year') }}"
                            placeholder="2024"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    {{-- ジャンル --}}
                    <div class="flex flex-col w-40">
                        <label for="genre" class="text-sm text-gray-600 mb-1">ジャンル</label>
                        <select id="genre" name="genre"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">指定なし</option>
                            @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" @if(request('genre')==$genre->id) selected @endif>
                                {{ $genre->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 価格 + 評価 --}}
                    <div class="flex gap-2 w-full max-w-xs">
                        <div class="flex flex-col flex-1">
                            <label for="price_min" class="text-sm text-gray-600 mb-1">最小価格</label>
                            <input id="price_min" type="number" name="price_min" value="{{ request('price_min') }}"
                                class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="flex flex-col flex-1">
                            <label for="price_max" class="text-sm text-gray-600 mb-1">最大価格</label>
                            <input id="price_max" type="number" name="price_max" value="{{ request('price_max') }}"
                                class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="flex flex-col flex-1 min-w-[120px]">
                            <label for="rating" class="text-sm text-gray-600 mb-1">評価</label>
                            <select id="rating" name="rating"
                                class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">指定なし</option>
                                @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" @if(request('rating')==$i) selected @endif>
                                    {{ str_repeat('★', $i) }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- ボタン --}}
                    <div class="flex gap-2 mt-4 sm:mt-0 sm:ml-auto">
                        <a href="{{ route('books.index', ['filter' => request('filter', 'all')]) }}"
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-500 rounded-lg transition text-center">クリア</a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg shadow transition text-center">検索</button>
                    </div>
                </form>

                {{-- 並び順 --}}
                <div class="flex gap-4 mb-4 text-sm">
                    @php
                    $columns = [
                        'created_at' => '登録順',
                        'rating' => '評価順',
                        'price' => '価格順',
                    ];
                    @endphp

                    @foreach($columns as $field => $label)
                        @php
                        $isCurrent = request('sort_field', 'created_at') === $field;
                        $currentOrder = $isCurrent ? request('sort_order', 'desc') : 'desc';
                        $nextOrder = ($isCurrent && $currentOrder === 'desc') ? 'asc' : 'desc';
                        $queryParams = array_merge(request()->except(['page','sort_field','sort_order']), [
                            'sort_field' => $field,
                            'sort_order' => $nextOrder,
                        ]);
                        @endphp
                        <a href="{{ route('books.index', $queryParams) }}"
                            class="px-3 py-1 rounded-lg border
                                {{ $isCurrent ? 'bg-green-600 text-white border-green-700' : 'bg-gray-200 text-gray-700 border-gray-300' }}">
                            {{ $label }}
                            @if($isCurrent)
                                {!! $currentOrder === 'desc' ? '▼' : '▲' !!}
                            @endif
                        </a>
                    @endforeach
                </div>

                {{-- タブ --}}
                <div class="flex gap-4 mb-6">
                    @php
                    $filters = ['all' => 'すべて', 'favorites' => 'お気に入り', 'recommended' => 'おすすめ'];
                    @endphp
                    @foreach($filters as $key => $label)
                    <a href="{{ route('books.index', array_merge(request()->except('page','filter'), ['filter' => $key])) }}"
                        class="px-4 py-2 rounded-lg transition
                            {{ request('filter', 'all') === $key ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>

                {{-- 本一覧 --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($books as $book)
                    @php
                    $isFavorited = in_array($book->id, $favoriteBookIds ?? []);
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-5 flex flex-col">
                        <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center mb-4">
                            @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}"
                                alt="{{ $book->title }} / {{ $book->author }}"
                                class="h-full w-full object-contain hover:scale-105 transition bg-white">
                            @else
                            <span class="text-gray-400 italic">No Image</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 truncate">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $book->author }}</p>
                        <p class="mt-2 font-semibold text-green-700">¥{{ number_format($book->price) }}</p>

                        <p class="text-yellow-500 mt-1">
                            @if($book->rating)
                            {{ str_repeat('★', $book->rating) }}{{ str_repeat('☆', 5 - $book->rating) }}
                            @else
                            <span class="text-gray-400">未評価</span>
                            @endif
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($book->genres as $genre)
                            <span class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        {{-- ボタン --}}
                        <div class="mt-4 flex gap-2">
                            @if(request('filter') === 'recommended')
                                {{-- おすすめタブ → 詳細だけ --}}
                                <a href="{{ route('books.show', $book->id) }}"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">詳細</a>
                            @else
                                {{-- 通常タブ --}}
                                <a href="{{ route('books.show', $book->id) }}"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">詳細</a>
                                <a href="{{ route('books.edit', $book->id) }}"
                                    class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition">編集</a>

                                <button
                                    type="button"
                                    class="favorite-btn px-3 py-1 text-sm font-bold rounded-lg transition
                                                {{ $isFavorited ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-gray-400 text-black hover:bg-gray-500' }}"
                                    data-url="{{ route('books.favorite.toggle', $book->id) }}">
                                    {{ $isFavorited ? 'お気に入り' : 'お気に入り' }}
                                </button>

                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition">削除</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ページネーション --}}
                <div class="mt-8">
                    {{ $books->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Ajax お気に入り --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".favorite-btn").forEach(button => {
                button.addEventListener("click", async () => {
                    const url = button.dataset.url;
                    try {
                        const response = await fetch(url, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json",
                            },
                        });
                        if (!response.ok) throw new Error("通信エラー");
                        const data = await response.json();

                        if (data.status === "added") {
                            button.textContent = "お気に入り";
                            button.classList.replace("bg-gray-400", "bg-red-600");
                            button.classList.replace("text-black", "text-white");
                            button.classList.replace("hover:bg-gray-500", "hover:bg-red-700");
                        } else if (data.status === "removed") {
                            button.textContent = "お気に入り";
                            button.classList.replace("bg-red-600", "bg-gray-400");
                            button.classList.replace("text-white", "text-black");
                            button.classList.replace("hover:bg-red-700", "hover:bg-gray-500");
                        }
                    } catch (err) {
                        console.error(err);
                        alert("お気に入りの更新に失敗しました");
                    }
                });
            });
        });
    </script>
</x-app-layout>
