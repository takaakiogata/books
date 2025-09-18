<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">新規本登録</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">

                        {{-- タイトル、著者、出版社 --}}
                        <div>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="タイトル" class="border rounded px-3 py-2 w-full" />
                            @error('title')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input type="text" name="author" value="{{ old('author') }}" placeholder="著者" class="border rounded px-3 py-2 w-full" />
                            @error('author')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input type="text" name="publisher" value="{{ old('publisher') }}" placeholder="出版社" class="border rounded px-3 py-2 w-full" />
                            @error('publisher')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 発売年・価格 --}}
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <input type="text" name="published_year" value="{{ old('published_year') }}" placeholder="発売年" class="border rounded px-3 py-2 w-full" />
                                @error('published_year')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="number" name="price" value="{{ old('price') }}" placeholder="価格" class="border rounded px-3 py-2 w-40" />
                                @error('price')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ジャンル選択 --}}
                        <div class="flex gap-2">
                            @php
                            $genreOptions = ['SF','ミステリー','ホラー','ファンタジー'];
                            @endphp

                            @for ($i = 0; $i < 3; $i++)
                                <select name="genres[]" class="border rounded px-3 py-2 flex-1">
                                <option value="">選択してください</option>
                                @foreach($genreOptions as $genreName)
                                <option value="{{ $genreName }}" @if(old("genres.$i")===$genreName) selected @endif>
                                    {{ $genreName }}
                                </option>
                                @endforeach
                                </select>
                                @endfor
                        </div>
                        <div>
                            <label for="rating" class="font-semibold">評価（1〜5）</label>
                            <select name="rating" id="rating" class="border rounded px-3 py-2 w-40">
                                <option value="">選択してください</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @if(old('rating', $book->rating ?? '') == $i) selected @endif>
                                    {{ $i }} ★
                                    </option>
                                    @endfor
                            </select>
                            @error('rating')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 感想 --}}
                        <div>
                            <textarea name="comment" placeholder="感想" class="border rounded px-3 py-2 w-full" rows="3">{{ old('comment') }}</textarea>
                            @error('comment')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 画像 --}}
                        <div>
                            <input type="file" name="image" />
                            @error('image')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ボタン --}}
                        <div class="flex gap-2 mt-4">
                            <button type="submit" class="px-4 py-2 border rounded hover:bg-gray-100">登録</button>
                            <a href="{{ route('books.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">戻る</a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>