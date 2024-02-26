<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypesDeveloper extends Model
{
    use HasFactory;

    public function developers()
    {
        return $this->hasMany(Developer::class);
    }
}
