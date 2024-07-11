<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
    
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'profile',
        'birthdate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   

    public function investments()
    {
        return $this->hasOne(Investment::class);
    }

     public function investment()
    {
        return $this->hasOne(Investment::class);
    }
    

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Define children relationship
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function commissionsGiven()
    {
        return $this->hasMany(Commission::class, 'commission_from');
    }


}
