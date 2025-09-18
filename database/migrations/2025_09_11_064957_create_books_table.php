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
            $table->string('title');        
            $table->string('author');       
            $table->string('publisher');    
            $table->smallInteger('published_year');
            $table->integer('price');       
            $table->text('comment')->nullable(); 
            $table->string('image_path')->nullable(); 
            $table->unsignedTinyInteger('rating')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
}
