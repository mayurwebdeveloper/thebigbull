<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'staff_id',
        'start_time',
        'end_time',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function responseAnswers()
    {
        return $this->hasMany(ResponseAnswer::class);
    }


}
