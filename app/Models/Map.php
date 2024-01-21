<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Map extends Model
{
    use HasFactory;

    protected $table = 'map';

    protected $appends = ['URLgeoJson'];

    protected $guarded = [];

    protected $fillable = ['name', 'geoJson'];

    public function getURLgeoJsonAttribute()
    {
        return Storage::disk('public')->url('/map/geojson/'.$this->attributes['geoJson']);
    }

}
