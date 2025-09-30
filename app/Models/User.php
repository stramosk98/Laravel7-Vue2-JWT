<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'api_token',
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
        $this->api_token = hash('sha256', \Illuminate\Support\Str::random(60));
        $this->save();

        return $this->api_token;
    }
}
