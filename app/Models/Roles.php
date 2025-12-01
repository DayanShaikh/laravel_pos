<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title'
    ];

    public function permissions()
    {
        return $this->hasMany(Permissions::class, 'role_id');
    }
}
