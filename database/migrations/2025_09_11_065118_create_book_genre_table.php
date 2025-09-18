<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookGenreTable extends Migration
{
    public function up()
    {
        Schema::create('book_genre', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
            $table->primary(['book_id', 'genre_id']);
            $table->timestamps(); 
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_genre');
    }
}
