<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public const ORDERS_TYPE = 1;
    public const STOCK_TYPE = 2;
    public const CLIENTS_TYPE = 3;

    protected $fillable = [
        'name', 'type',
    ];

    protected $casts = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
