<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subject_department extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id','department_id'];
}
