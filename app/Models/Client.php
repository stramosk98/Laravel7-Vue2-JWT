<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'city', 'state',
    ];

    protected $casts = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
