<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-green-800 leading-tight flex items-center gap-2">
            📘 本の詳細
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-8">

                <div class="flex flex-col md:flex-row gap-8">
                    {{-- 画像 --}}
                    <div class="md:w-1/3">
                        <div class="w-full h-64 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                            @if($book->image_path)
                                <img src="{{ asset('storage/' . $book->image_path) }}" 
                                     alt="{{ $book->title }}" 
                                     class="h-full w-full object-cover">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </div>
                    </div>

                    {{-- 本の情報 --}}
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $book->title }}</h3>
                        <p class="text-lg text-gray-600 mb-2">著者: {{ $book->author }}</p>
                        <p class="text-lg text-gray-600 mb-2">出版社: {{ $book->publisher }}</p>
                        <p class="text-green-700 font-semibold text-lg mb-2">¥{{ number_format($book->price) }}</p>

                        {{-- ★評価 --}}
                        <p class="text-yellow-500 mb-4">
                            @if($book->rating)
                                {{ str_repeat('★', $book->rating) }}
                                {{ str_repeat('☆', 5 - $book->rating) }}
                            @else
                                <span class="text-gray-400">未評価</span>
                            @endif
                        </p>

                        {{-- ジャンル --}}
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach($book->genres as $genre)
                                <span class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-sm">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        {{-- 感想 --}}
                        <p class="text-gray-700 whitespace-pre-line">{{ $book->comment }}</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('books.index') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">戻る</a>
                    <a href="{{ route('books.edit', $book->id) }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">編集</a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" 
                          onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow transition">削除</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
