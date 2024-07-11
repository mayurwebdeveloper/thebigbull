<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    // Specify fillable fields
    protected $fillable = [
        'user_id',
        'commission_amount',
        'before_commission_added',
        'after_commission_added',
        'commission_date',
        'commission_from',
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship to the user who gave the commission
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'commission_from');
    }
}
