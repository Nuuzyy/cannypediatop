<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['judul', 'penulis', 'stok'])]
class Book extends Model
{
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}

