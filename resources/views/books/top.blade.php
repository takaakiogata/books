<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">本一覧</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- 検索フォーム --}}
                <form method="GET" action="{{ route('books.index') }}" class="flex flex-wrap gap-2 mb-4">
                    <input type="text" name="keyword" placeholder="タイトルで検索" class="border rounded px-3 py-2 flex-1 min-w-[160px]" />
                    <input type="text" name="author" placeholder="著者名" class="border rounded px-3 py-2 min-w-[140px]" />
                    <input type="number" name="price_min" placeholder="最小価格" class="border rounded px-3 py-2 w-28" />
                    <input type="number" name="price_max" placeholder="最大価格" class="border rounded px-3 py-2 w-28" />
                    <button type="submit" class="px-4 py-2 border rounded hover:bg-gray-100">検索</button>

                    <a href="{{ route('books.create') }}" class="ml-auto px-4 py-2 border rounded hover:bg-gray-100">新規登録</a>
                </form>


                {{-- 一覧グリッド --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($books as $book)
                    <div class="border rounded-lg p-4 flex flex-col">
                        <div class="w-full h-48 bg-gray-100 mb-4 flex items-center justify-center">
                            @if($book->image_path)
                                <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-gray-400">画像</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $book->author }}</p>
                        <p class="mt-2 font-bold">¥{{ number_format($book->price) }}</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($book->genres as $genre)
                                <span class="px-2 py-1 border rounded-full text-sm">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('books.show', $book->id) }}" class="px-3 py-1 border rounded hover:bg-gray-100">詳細</a>
                            <a href="{{ route('books.edit', $book->id) }}" class="px-3 py-1 border rounded hover:bg-gray-100">編集</a>

                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 border rounded hover:bg-gray-100">削除</button>
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
