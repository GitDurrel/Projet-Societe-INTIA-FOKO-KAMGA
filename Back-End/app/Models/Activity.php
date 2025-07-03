<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'titre',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
    ];

    public function personnel()
    {
        return $this->belongsTo(User::class, 'personnel_id');
    }
} 