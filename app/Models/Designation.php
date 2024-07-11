<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->hasMany(Staff::class, 'designation_id');
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'designation_id');
    }
}
