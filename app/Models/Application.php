<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'cv',
        'cover_letter',
        'status',
    ];

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class, 'job_id');
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }
}
