<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;


class video extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'file_path',
        'size_video',
        'number',
        'course_id'
    ];


    public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }
    public function course()
    {
        return $this->belongsTo(course::class);
    }

}
