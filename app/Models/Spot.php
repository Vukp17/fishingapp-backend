<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;
    protected $fillable = [
     'image_id','user_id','title','description','lat','lng','created_at','updated_at','name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
