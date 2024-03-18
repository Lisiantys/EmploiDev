<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'profil_image',
        'first_name',
        'surname',
        'cv',
        'cover_letter',
        'is_free',
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
        return $this->belongsToMany(ProgrammingLanguage::class, 'developers_languages');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}