<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    use HasFactory;

    protected $table = 'tanaman';

    protected $guarded = [];

    protected $appends = [
        'ImageUrl'
    ];

    public function area()
    {
        return $this->belongsTo(Map::class,'map_id','id');
    }

    public function getImageUrlAttribute()
    {
        return url('storage/tanaman/' . $this->image);
    }
}
