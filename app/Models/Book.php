<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    // 更新可能カラムを指定
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'published_year',
        'price',
        'comment',
        'image_path',
        'rating',
        'user_id',
    ];


    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id')
            ->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
