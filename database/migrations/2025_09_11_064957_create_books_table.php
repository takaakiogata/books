<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // 本のタイトル
            $table->string('author');       // 著者
            $table->string('publisher');    // 出版社
            $table->smallInteger('published_year');
            $table->integer('price');       // 価格
            $table->text('comment')->nullable(); // コメント
            $table->string('image_path')->nullable(); // 画像のパス
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
}
