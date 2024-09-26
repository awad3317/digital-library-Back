<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;


class academic_year extends Model
{
    use HasFactory;

    protected $fillable = ['year'];


    public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }
    public function project()
    {
        return $this->hasMany(project::class);
    }

    public function subject()
    {
        return $this->belongsToMany(subject::class,'academicyear_subject');
    }
}
