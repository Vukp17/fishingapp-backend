<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    use HasApiTokens;


    public function spots()
    {
        return $this->hasMany(Spot::class);
    }
}
