<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class course extends Model
{
    use HasFactory;
    use FilterQueryString;
    protected $filters = ['like','category_id','accepted'];
    protected $fillable = [
        'name',
        'description',
        'image',
        'accepted',
        'category_id'
    ];
    public function category_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('category_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
    public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }

    public function video()
    {
        return $this->hasMany(video::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }
}
