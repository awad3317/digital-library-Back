<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class subject extends Model
{
    use HasFactory,FilterQueryString;
    protected $filters = ['like', 'department_id', 'academic_year_id','semester'];
    protected $fillable = ['name','semester','department_id'];


    public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }

>>>>>>> Stashed changes
    public function academic_years()
    {
        return $this->belongsToMany(academic_year::class,'academicyear_subjects');
    }

    public function departments()
    {
        return $this->belongsToMany(department::class,'subject_departments','subject_id','department_id');
    }

    public function Lecture()
    {
        return $this->hasMany(Lecture::class);
    }


}
