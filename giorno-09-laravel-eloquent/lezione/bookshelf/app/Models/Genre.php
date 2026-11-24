<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Genre extends Model
{
    use HasFactory;

    // Un genere ha molti libri (1-N, lato "uno")
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
