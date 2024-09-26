<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Author extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function getIdAttribute($id)
   {
       return Crypt::encrypt($id);
   }
    /**
     * The roles that belong to the Author
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

     public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }

    public function Books()
    {
        return $this->belongsToMany(Book::class,'author_books','author_id','book_id');
    }
    public function scopeRand_Pag($query){
        return $query->inRandomOrder()->paginate(10);
    }
}
