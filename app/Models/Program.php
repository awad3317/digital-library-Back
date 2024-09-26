<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Crypt;

class Program extends Model
{
    use HasFactory;
    use FilterQueryString;
    protected $filters = ['like','category_id','accepted'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'File_path',
        'Name',
        'Description',
        'image',
        'Accepted',
        'Version',
        'size_Program',
        'category_id'
    ];
    public function scopeSelect_Progeam_With_category_name($query){
        return $query->select(
        'programs.id', 'programs.Name as name',
        'programs.File_path', 'programs.Description',
        'programs.image', 'programs.Accepted',
        'programs.Version', 'programs.size_Program','categories.name AS category_name');
    }
    public function category_id($query, $value) {
        if (!empty($value) && preg_match('/^[a-f0-9]{32}$/i', $value)) {
            return $query->where('category_id', '=', Crypt::decrypt($value));
        }
        return $query;
    }
    public function getIdAttribute($id)
    {
        return Crypt::encrypt($id);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

}
