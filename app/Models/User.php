<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = ['parking'];
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
        return $token;
    }

    public function hotel(): HasOne
    {
        return $this->hasOne(Hotel::class, 'manager_id');
    }
    public function customer_parking(): HasMany
    {
        return $this->hasMany(CustomerParking::class, 'customer_id');
    }
    public function parking(): Attribute
    {
        return new Attribute(
            get: fn () => $this->customer_parking->where('valid',true)->first(),
        );
    }
}
