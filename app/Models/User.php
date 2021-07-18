<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions; //Добавили трейт HasRolesAndPermissions

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'login',
        'name',
        'email',
        'type',
        'github',
        'city',
        'phone',
        'birthday',
        'is_finished',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole(... $roles ) {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }
    public function position() {
        return $this->belongsTo(Position::class);
    }
}
