<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    protected $fillable = [
        'type', 'numero_police', 'date_debut', 'date_fin', 'montant', 'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
} 