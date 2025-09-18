<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            編集：{{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <label class="block">
                        <span class="text-gray-700">タイトル</span>
                        <input type="text" name="title" value="{{ old('title', $book->title) }}" class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </label>

                    <label class="block">
                        <span class="text-gray-700">著者</span>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}" class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('author') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </label>

                    <div class="flex gap-2">
                        <label class="flex-1">
                            <span class="text-gray-700">発売年</span>
                            <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}" class="mt-1 block w-full border rounded px-3 py-2" />
                            @error('published_year') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </label>

                        <label class="w-40">
                            <span class="text-gray-700">価格 (円)</span>
                            <input type="number" name="price" value="{{ old('price', $book->price) }}" class="mt-1 block w-full border rounded px-3 py-2" />
                            @error('price') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </label>
                    </div>

                    <div class="flex gap-2">
                        @php
                            $genreOptions = ['SF','ミステリー','ホラー','ファンタジー'];
                            // Bookに紐づくジャンル名配列（順番は保存時のままではない可能性がある）
                            $selectedGenres = $book->genres->pluck('name')->toArray();
                        @endphp

                        @for ($i = 0; $i < 3; $i++)
                            <select name="genres[]" class="border rounded px-3 py-2 flex-1">
                                <option value="">選択してください</option>
                                @foreach($genreOptions as $genreName)
                                    <option value="{{ $genreName }}"
                                        @if(old("genres.$i", $selectedGenres[$i] ?? '') === $genreName) selected @endif>
                                        {{ $genreName }}
                                    </option>
                                @endforeach
                            </select>
                        @endfor
                    </div>

                    <div class="mt-4">
                        <label for="rating" class="font-semibold">評価（1〜5）</label>
                        <select name="rating" id="rating" class="border rounded px-3 py-2 w-40">
                            <option value="">選択してください</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" @if(old('rating', $book->rating ?? '') == $i) selected @endif>
                                    {{ $i }} ★
                                </option>
                            @endfor
                        </select>
                        @error('rating') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <label class="block mt-4">
                        <span class="text-gray-700">感想</span>
                        <textarea name="comment" class="mt-1 block w-full border rounded px-3 py-2" rows="4">{{ old('comment', $book->comment) }}</textarea>
                        @error('comment') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">画像アップロード</span>
                        <input type="file" name="image" class="mt-1 block w-full" />
                        @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" class="mt-2 h-32">
                        @endif
                        @error('image') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </label>

                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="px-4 py-2 border rounded hover:bg-gray-100">保存</button>
                        <a href="{{ route('books.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">戻る</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
