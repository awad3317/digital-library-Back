<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class lecture_details extends Model
{
    use HasFactory,FilterQueryString;
    protected $filters = ['lecture_id'];
    protected $fillable = [
        'file_path',
        'description',
        'lecture_id'
   ];
    public function lecture_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('lecture_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
   public function getIdAttribute($id)
   {
       return Crypt::encrypt($id);
   }

   public function lecture()
    {
        return $this->belongsTo(lecture::class);
    }
}
