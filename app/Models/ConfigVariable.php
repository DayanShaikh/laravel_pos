<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConfigType;
use Illuminate\Support\Facades\Storage;

class ConfigVariable extends Model
{
    use HasFactory;
    protected $fillable = [
        'config_type_id',
        'title',
        'notes',
        'type',
        'default_values',
        'key',
        'value',
        'sortorder',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            return url(Storage::url($this->image));
        } else {
            return $this->image;
        }
    }

    public function ConfigType()
    {
        return $this->belongsTo(ConfigType::class);
    }
}
