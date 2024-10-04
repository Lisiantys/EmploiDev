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
        'is_validated',
        'contract_id',
        'year_id',
        'location_id',
        'type_id',
        'company_id'
    ];

    protected $with = ['company', 'programmingLanguages', 'location', 'typesDeveloper', 'typesContract' , 'yearsExperience'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

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
        return $this->belongsToMany(ProgrammingLanguage::class, 'job_languages', 'job_offer_id', 'programming_language_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}
