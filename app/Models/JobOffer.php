<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'contract_id',
        'year_id',
        'location_id',
        'type_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function yearsExperience()
    {
        return $this->belongsTo(YearsExperience::class, 'year_id');
    }

    public function typesDeveloper()
    {
        return $this->belongsTo(TypesDeveloper::class, 'type_id');
    }

    public function typesContract()
    {
        return $this->belongsTo(TypesContract::class, 'contract_id');
    }

    public function programmingLanguages()
    {
        return $this->belongsToMany(ProgrammingLanguage::class, 'job_languages');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}
