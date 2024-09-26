<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class team extends Model
{
    use HasFactory ,FilterQueryString;

    protected $filters = ['like'];
    protected $fillable = ['name'];


    public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }
    
    public function project()
    {
        return $this->belongsToMany(project::class,'team_project','team_id','project_number');
    }
}
