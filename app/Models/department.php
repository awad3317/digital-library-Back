<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class department extends Model
{
    use HasFactory;
    protected $fillable = ['name'];


    public function getIdAttribute($id)
   {
       return Crypt::encrypt($id);
   }
    public function project()
    {
        return $this->hasMany(project::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(subject::class,'subject_departments','department_id','subject_id');
    }
}
