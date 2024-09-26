<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Publisher extends Model
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
    
    public function Book()
    {
        return $this->hasMany(Book::class);
    }
}
