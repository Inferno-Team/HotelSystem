<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getToken()
    {
        $token = null;
        if (isset($this->remember_token))
            $token = $this->remember_token;
        else {
            $token = $this->createToken('authToken')->plainTextToken;
            $this->remember_token = $token;
            $this->save();
        }

        return $this->createToken('authToken')->plainTextToken;
    }

    public function hotel(): HasOne
    {
        return $this->hasOne(Hotel::class, 'manager_id');
    }
}
