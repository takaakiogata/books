<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-green-800 leading-tight flex items-center gap-2">
            📗 新規本登録
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-8">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- タイトル --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">タイトル <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('title')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 著者 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">著者 <span class="text-red-500">*</span></label>
                        <input type="text" name="author" value="{{ old('author') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('author')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 出版社 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">出版社 <span class="text-red-500">*</span></label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('publisher')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 発売年・価格 --}}
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">発売年 <span class="text-red-500">*</span></label>
                            <input type="text" name="published_year" value="{{ old('published_year') }}"
                                class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            @error('published_year')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">価格 <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}"
                                class="mt-1 w-40 border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            @error('price')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ジャンル選択 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ジャンル <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            @php
                            $novelGenres = [
                            'ミステリー',
                            'ホラー',
                            'ファンタジー',
                            'SF',
                            '恋愛',
                            '青春',
                            '歴史・時代',
                            '冒険・アクション',
                            'ヒューマンドラマ',
                            '純文学',
                            ];
                            @endphp
                            @for ($i = 0; $i < 3; $i++)
                                <select name="genres[]" class="border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" @if($i==0) required @endif>
                                <option value="">選択してください</option>
                                @foreach($novelGenres as $genreName)
                                <option value="{{ $genreName }}" @if(old("genres.$i")===$genreName) selected @endif>
                                    {{ $genreName }}
                                </option>
                                @endforeach
                                </select>
                                @endfor
                        </div>
                        @error('genres')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 評価 --}}
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700">評価 <span class="text-red-500">*</span></label>
                        <select name="rating" id="rating" class="border-gray-300 rounded-lg px-3 py-2 w-40 focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            <option value="">選択してください</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" @if(old('rating')==$i) selected @endif>
                                {{ str_repeat('★', $i) }}
                                </option>
                                @endfor
                        </select>
                        @error('rating')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 感想 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">感想</label>
                        <textarea name="comment" rows="4"
                            class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('comment') }}</textarea>
                    </div>

                    {{-- 画像 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">画像</label>
                        <input type="file" name="image" class="mt-1">
                    </div>

                    {{-- ボタン --}}
                    <div class="flex gap-3">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                            登録
                        </button>
                        <a href="{{ route('books.index') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                            戻る
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>