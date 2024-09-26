<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class academicyear_subject extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id','academic_year_id'];
}
