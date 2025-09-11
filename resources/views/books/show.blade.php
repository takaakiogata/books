<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $book['title'] ?? '詳細' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 bg-gray-100 h-64 flex items-center justify-center">
                        <span class="text-gray-400">画像</span>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-2xl font-bold">{{ $book['title'] }}</h3>
                        <p class="text-gray-600 mt-1">著者：{{ $book['author'] }}</p>
                        <p class="mt-2">発売年：{{ $book['years'] }}</p>
                        <p class="mt-2 font-bold text-lg">¥{{ number_format($book['price']) }}</p>

                        <div class="mt-4">
                            <div class="flex gap-2">
                                @if(!empty($book['genres']))
                                @foreach($book['genres'] as $g)
                                <span class="px-2 py-1 border rounded-full text-sm">{{ $g }}</span>
                                @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-medium">感想</h4>
                            <p class="mt-2 text-gray-700">{{ $book['comment'] }}</p>
                        </div>

                        <div class="mt-6 flex gap-2">
                            <a href="{{ route('books.edit', $book['id']) }}" class="px-4 py-2 border rounded hover:bg-gray-100">編集</a>

                            <form action="#" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 border rounded hover:bg-gray-100">削除</button>
                            </form>

                            <a href="{{ route('dashboard') }}" class="ml-auto px-4 py-2 border rounded hover:bg-gray-100">一覧へ戻る</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>