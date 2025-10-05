<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-green-800 leading-tight flex items-center gap-3">
            📘 本の詳細
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-10">

                <div class="flex flex-col md:flex-row gap-10">
                    {{-- 画像 --}}
                    <div class="md:w-2/5">
                        <div class="w-full md:h-[28rem] bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                            @if($book->image_path)
                                <img src="{{ asset('storage/' . $book->image_path) }}" 
                                     alt="{{ $book->title }}" 
                                     class="h-full w-full object-contain">
                            @else
                                <span class="text-gray-400 text-lg">No Image</span>
                            @endif
                        </div>
                    </div>

                    {{-- 本の情報 --}}
                    <div class="md:w-3/5 flex flex-col justify-start">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">{{ $book->title }}</h3>
                        <p class="text-xl text-gray-700 mb-3">著者: {{ $book->author }}</p>
                        <p class="text-xl text-gray-700 mb-3">出版社: {{ $book->publisher }}</p>
                        <p class="text-green-700 font-semibold text-2xl mb-4">¥{{ number_format($book->price) }}</p>

                        {{-- ★評価 --}}
                        <p class="text-yellow-500 text-lg mb-4">
                            @if($book->rating)
                                {{ str_repeat('★', $book->rating) }}
                                {{ str_repeat('☆', 5 - $book->rating) }}
                            @else
                                <span class="text-gray-400">未評価</span>
                            @endif
                        </p>

                        {{-- ジャンル --}}
                        <div class="mb-6 flex flex-wrap gap-3">
                            @foreach($book->genres as $genre)
                                <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-base">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        {{-- 感想 --}}
                        <p class="text-lg text-gray-700 whitespace-pre-line">{{ $book->comment }}</p>
                    </div>
                </div>

                {{-- 戻るボタン --}}
                <div class="mt-10 flex gap-3">
                    <a href="{{ route('books.index') }}" 
                       class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-lg transition">戻る</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
