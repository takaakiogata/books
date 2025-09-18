<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                {{-- タイトル --}}
                <h1 class="text-2xl font-bold mb-4">{{ $book->title }}</h1>

                {{-- 著者・出版社 --}}
                <p><span class="font-semibold">著者：</span>{{ $book->author }}</p>
                <p><span class="font-semibold">出版社：</span>{{ $book->publisher }}</p>
                <p><span class="font-semibold">発売年：</span>{{ $book->published_year }}</p>
                <p><span class="font-semibold">価格：</span>{{ number_format($book->price) }} 円</p>

                {{-- ジャンル --}}
                <div class="mt-4">
                    <span class="font-semibold">ジャンル：</span>
                    @if($book->genres->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($book->genres as $genre)
                        <span class="px-2 py-1 border rounded-full text-sm">
                            {{ $genre->name }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <span class="text-gray-500">未設定</span>
                    @endif
                </div>


                {{-- 感想 --}}
                <div class="mt-4">
                    <span class="font-semibold">感想：</span>
                    <p class="mt-1 whitespace-pre-line">{{ $book->comment }}</p>
                </div>

                {{-- 画像 --}}
                @if($book->image_path)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $book->image_path) }}" alt="Book image" class="max-h-80 rounded border">
                </div>
                @endif

                {{-- アクションボタン --}}
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('books.edit', $book->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        編集
                    </a>

                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            削除
                        </button>
                    </form>

                    <a href="{{ route('books.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">
                        一覧へ戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>