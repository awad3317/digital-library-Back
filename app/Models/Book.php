<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class Book extends Model
{
    use HasFactory;
    use FilterQueryString;
    protected $filters = ['like', 'category_id', 'author_id','accepted'];
    protected $fillable = [
        'name',
        'file_path',
        'image',
        'Publisher_id',
        'description',
        'accepted',
        'book_audio',
        'edition',
        'size_Book',
        'size_audio_Book',
        'category_id',
   ];

   public function scopeJoin_With_3tables($query){
    return $query->join('categories','categories.id','=','Books.category_id')
    ->join('author_books','books.id','=','author_books.book_id')
    ->join('authors','authors.id','=','author_books.author_id');
   }
   public function scopeSelect_Book_With_category_name($query){
    return $query->select(
    'Books.id','Books.name as name',
    'books.file_path','books.image',
    'books.Publisher_id','books.description',
    'books.accepted','books.book_audio',
    'books.edition','books.size_Book',
    'books.size_audio_Book','categories.name AS category_name');
   }
public function category_id($query, $value) {
    if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
        return $query->where('category_id', '=', Crypt::decrypt($value));
    }
    return $query;
}
public function author_id($query, $value) {
    if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
        return $query->where('author_id', '=', Crypt::decrypt($value));
    }
    return $query;
}
   public function getIdAttribute($id)
   {
       return Crypt::encrypt($id);
   }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function Publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function Authors()
    {
        return $this->belongsToMany(Author::class,'author_books','book_id','author_id');
    }
}
