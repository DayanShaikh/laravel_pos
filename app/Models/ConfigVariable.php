<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConfigType;

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

    public function ConfigType()
    {
        return $this->belongsTo(ConfigType::class);
    }
}
