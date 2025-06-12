<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieTournoi extends Model
{
    protected $fillable = ['nom'];

    // Relationships
    public function tournois() { return $this->hasMany(Tournoi::class); }
}
