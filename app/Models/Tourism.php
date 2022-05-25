<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tourism extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
        'city',
        'price',
        'rating',
        'coordinate',
        'thumbnail'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
