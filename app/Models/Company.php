<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'profil_image',
        'name',
    ];

    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
