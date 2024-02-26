<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammingLanguage extends Model
{
    use HasFactory;

    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'developers_languages');
    }

    public function jobOffers()
    {
        return $this->belongsToMany(JobOffer::class, 'job_languages');
    }
}

