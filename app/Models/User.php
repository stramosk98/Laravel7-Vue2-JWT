<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public const ADMIN_TYPE = 1;
    public const LOGISTICS_MANAGER_TYPE = 2;
    public const USER_TYPE = 3;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
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
