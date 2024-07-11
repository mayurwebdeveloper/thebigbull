<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
