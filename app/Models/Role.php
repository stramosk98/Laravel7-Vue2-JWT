<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ADMIN_TYPE = 1;
    public const LOGISTICS_MANAGER_TYPE = 2;
    public const USER_TYPE = 3;

    protected $fillable = [
        'name', 'description',
    ];

    protected $casts = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
