<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            編集：{{ $book['title'] ?? '' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- 実装段階では action を更新用のルートに差し替えます --}}
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <label class="block">
                            <span class="text-gray-700">タイトル</span>
                            <input type="text" name="title" value="{{ $book['title'] }}" class="mt-1 block w-full border rounded px-3 py-2" />
                        </label>

                        <label class="block">
                            <span class="text-gray-700">著者</span>
                            <input type="text" name="author" value="{{ $book['author'] }}" class="mt-1 block w-full border rounded px-3 py-2" />
                        </label>

                        <div class="flex gap-2">
                            <label class="flex-1">
                                <span class="text-gray-700">発売年</span>
                                <input type="number" name="years" value="{{ $book['years'] }}" class="mt-1 block w-full border rounded px-3 py-2" />
                            </label>

                            <label class="w-40">
                                <span class="text-gray-700">価格 (円)</span>
                                <input type="number" name="price" value="{{ $book['price'] }}" class="mt-1 block w-full border rounded px-3 py-2" />
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-gray-700">ジャンル（複数選択可）</span>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($genres as $g)
                                <label class="inline-flex items-center gap-2 px-2 py-1 border rounded">
                                    <input type="checkbox" name="genres[]" value="{{ $g }}" {{ in_array($g, $book['genres']) ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $g }}</span>
                                </label>
                                @endforeach
                            </div>
                        </label>

                        <label class="block">
                            <span class="text-gray-700">感想</span>
                            <textarea name="comment" class="mt-1 block w-full border rounded px-3 py-2" rows="4">{{ $book['comment'] }}</textarea>
                        </label>

                        <label class="block">
                            <span class="text-gray-700">画像アップロード</span>
                            <input type="file" name="image" class="mt-1 block w-full" />
                        </label>

                        <div class="flex gap-2 mt-4">
                            <button type="submit" class="px-4 py-2 border rounded hover:bg-gray-100">保存</button>
                            <a href="{{ route('books.show', $book['id']) }}" class="px-4 py-2 border rounded hover:bg-gray-100">キャンセル</a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>