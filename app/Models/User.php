<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Correct base class
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; // Needed for user notifications, like password resets

class User extends Authenticatable // Use Authenticatable instead of Model
{
    use HasFactory, HasApiTokens, Notifiable; // Include Notifiable for user-related notifications

    protected $fillable = [
        'username', 'email', 'password', // Attributes you want to be mass assignable
    ];

    public function spots() // Keep any other relationships
    {
        return $this->hasMany(Spot::class);
        
    }
    public function languages() // Define the relationship with the Language model
    {
        return $this->belongsTo(Language::class);
    }
}
