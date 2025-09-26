<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-green-800 leading-tight flex items-center gap-2">
            📚 本一覧
        </h2>

        {{-- マイページリンク --}}
        <a href="{{ route('mypage.index') }}"
           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
            マイページ
        </a>
    </div>
</x-slot>


    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-8">

                {{-- 検索フォーム --}}
                <form method="GET" action="{{ route('books.index') }}"
                    class="flex flex-wrap items-end gap-4 mb-8 bg-green-50 p-6 rounded-xl border border-green-100">

                    {{-- タブ情報 hidden --}}
                    <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">

                    {{-- 検索入力欄 --}}
                    <div class="flex flex-col flex-1 min-w-[160px]">
                        <label class="text-sm text-gray-600 mb-1">タイトル</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="タイトルで検索"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="flex flex-col min-w-[140px]">
                        <label class="text-sm text-gray-600 mb-1">著者名</label>
                        <input type="text" name="author" value="{{ request('author') }}"
                            placeholder="著者名"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="flex flex-col w-28">
                        <label class="text-sm text-gray-600 mb-1">最小価格</label>
                        <input type="number" name="price_min" value="{{ request('price_min') }}"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="flex flex-col w-28">
                        <label class="text-sm text-gray-600 mb-1">最大価格</label>
                        <input type="number" name="price_max" value="{{ request('price_max') }}"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="flex flex-col w-32">
                        <label class="text-sm text-gray-600 mb-1">評価</label>
                        <select name="rating"
                            class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">指定なし</option>
                            @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @if(request('rating')==$i) selected @endif>
                                {{ str_repeat('★', $i) }}
                            </option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex gap-2 mt-4 sm:mt-0 sm:ml-auto">
                        <a href="{{ route('books.index', ['filter' => request('filter', 'all')]) }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">クリア</a>
                        <button type="submit"
                            class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg shadow transition">検索</button>
                        <a href="{{ route('books.create') }}"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">新規登録</a>
                    </div>
                </form>

                {{-- 並び順切替 --}}
                <div class="flex gap-4 mb-4 text-sm">
                    @php
                    $columns = [
                    'created_at' => '登録順',
                    'rating' => '評価順',
                    'price' => '価格順',
                    'title' => '五十音順'
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


                {{-- タブ切り替え --}}
                <div class="flex gap-4 mb-6">
                    <a href="{{ route('books.index', array_merge(request()->except('page','filter'), ['filter' => 'all'])) }}"
                        class="px-4 py-2 rounded-lg transition
        {{ request('filter', 'all') !== 'favorites' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        すべて
                    </a>

                    <a href="{{ route('books.index', array_merge(request()->except('page','filter'), ['filter' => 'favorites'])) }}"
                        class="px-4 py-2 rounded-lg transition
        {{ request('filter') === 'favorites' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        お気に入り
                    </a>
                </div>

                {{-- 一覧グリッド --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($books as $book)
                    @php
                    $isFavorited = in_array($book->id, $favoriteBookIds ?? []);
                    @endphp

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-5 flex flex-col">
                        <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center mb-4">
                            @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}"
                                alt="{{ $book->title }}"
                                class="h-full w-full object-contain hover:scale-105 transition bg-white">
                            @else
                            <span class="text-gray-400">No Image</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 truncate">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $book->author }}</p>
                        <p class="mt-2 font-semibold text-green-700">¥{{ number_format($book->price) }}</p>

                        {{-- ★評価 --}}
                        <p class="text-yellow-500 mt-1">
                            @if($book->rating)
                            {{ str_repeat('★', $book->rating) }}
                            {{ str_repeat('☆', 5 - $book->rating) }}
                            @else
                            <span class="text-gray-400">未評価</span>
                            @endif
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($book->genres as $genre)
                            <span class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('books.show', $book->id) }}"
                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">詳細</a>

                            <a href="{{ route('books.edit', $book->id) }}"
                                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition">編集</a>

                            {{-- お気に入りボタン --}}
                            <button
                                type="button"
                                class="favorite-btn px-3 py-1 text-sm font-bold rounded-lg transition
                                    {{ $isFavorited ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-gray-400 text-black hover:bg-gray-500' }}"
                                data-url="{{ route('books.favorite.toggle', $book->id) }}">
                                {{ $isFavorited ? 'お気に入り済み' : 'お気に入り' }}
                            </button>

                            {{-- 削除フォーム --}}
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition">削除</button>
                            </form>
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

    {{-- Ajax用スクリプト --}}
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
                            button.textContent = "お気に入り済み";
                            button.classList.remove("bg-gray-400", "text-black", "hover:bg-gray-500");
                            button.classList.add("bg-red-600", "text-white", "hover:bg-red-700");
                        } else if (data.status === "removed") {
                            button.textContent = "お気に入り";
                            button.classList.remove("bg-red-600", "text-white", "hover:bg-red-700");
                            button.classList.add("bg-gray-400", "text-black", "hover:bg-gray-500");
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