<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'email', 'telephone', 'adresse'
    ];

    public function assurances()
    {
        return $this->hasMany(Assurance::class);
    }
} 