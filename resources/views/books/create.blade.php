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
                        <input type="text" name="title" placeholder="タイトル" class="border rounded px-3 py-2 w-full" />
                        <input type="text" name="author" placeholder="著者" class="border rounded px-3 py-2 w-full" />
                        <input type="text" name="publisher" placeholder="出版社" class="border rounded px-3 py-2 w-full" />

                        {{-- 発売年・価格 --}}
                        <div class="flex gap-2">
                            <input type="text" name="published_year" placeholder="発売年" class="border rounded px-3 py-2 flex-1" />
                            <input type="number" name="price" placeholder="価格" class="border rounded px-3 py-2 w-40" />
                        </div>

                        <div class="flex gap-2">
                            @php
                            $genreOptions = ['SF','ミステリー','ホラー','ファンタジー'];
                            @endphp

                            @for ($i = 0; $i < 3; $i++)
                                <select name="genres[]" class="border rounded px-3 py-2 flex-1">
                                <option value="">選択してください</option>
                                @foreach($genreOptions as $genreName)
                                <option value="{{ $genreName }}">{{ $genreName }}</option>
                                @endforeach
                                </select>
                                @endfor
                        </div>



                        {{-- 感想 --}}
                        <textarea name="comment" placeholder="感想" class="border rounded px-3 py-2 w-full" rows="3"></textarea>

                        {{-- 画像 --}}
                        <input type="file" name="image" />

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