<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class project extends Model
{
    use HasFactory,FilterQueryString;
    protected $filters = ['like','department_id','academic_year_id'];
    protected $fillable = [
        'number',
        'title',
        'level',
        'file_path',
        'supervisor',
        'image',
        'description',
        'department_id',
        'academic_year_id'
    ];
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'number';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    public function department_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('department_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
    public function academic_year_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('academic_year_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
    public function getNumberAttribute($number)
    {
        return Crypt::encrypt($number);
    }
    public function academic_year()
    {
        return $this->belongsTo(academic_year::class);
    }

    public function department()
    {
        return $this->belongsTo(department::class);
    }

    public function team()
    {
        return $this->belongsToMany(team::class,'team_project','project_number','team_id');
    }
}
