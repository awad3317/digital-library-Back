<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class lecture extends Model
{
    use HasFactory ,FilterQueryString;
    protected $filters = ['like', 'lectures.subject_id', 'user_id','department_id'];
    protected $fillable = [
        'name',
        'file_path',
        'description',
        'number',
        'subject_id',
        'user_id'
   ];

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function subject_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('subject_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
    public function user_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('user_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
    public function department_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('department_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
   public function getIdAttribute($id)
   {
       return Crypt::encrypt($id);
   }
=======
>>>>>>> parent of b3e2e4e (fix filter lecture)
=======
>>>>>>> parent of b3e2e4e (fix filter lecture)
=======
>>>>>>> parent of b3e2e4e (fix filter lecture)

    public function subject()
    {
        return $this->belongsTo(subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lecture_details()
    {
        return $this->hasMany(lecture_details::class);
    }
}
