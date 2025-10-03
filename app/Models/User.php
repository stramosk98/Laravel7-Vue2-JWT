<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ADMIN_TYPE = 1;
    public const LOGISTICS_MANAGER_TYPE = 2;
    public const USER_TYPE = 3;

    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    protected $casts = [];

    /**
     * Generate a new API token for the user.
     *
     * @return string
     */
    public function generateApiToken()
    {
        $this->api_token = \Illuminate\Support\Str::random(60);
        $this->save();

        return $this->api_token;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->HasManyThrough(Permission::class, RolePermission::class, 'role_id', 'id', 'role_id', 'permission_id');
    }

    public function hasPermission($permissionName)
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    public function isAdmin()
    {
        return $this->role->id === self::ADMIN_TYPE;
    }

    public function isLogisticsManager()
    {
        return $this->role->id === self::LOGISTICS_MANAGER_TYPE;
    }

    public function isUser()
    {
        return $this->role->id === self::USER_TYPE;
    }
}
