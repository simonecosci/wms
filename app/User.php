<?php

namespace App;

use App\Models\Common\CrudModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends CrudModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function create(array $attributes = [], array $options = []) {
        if (isset($attributes['new_password'])) {
            $attributes['password'] = Hash::make($attributes['new_password']);
        }
        parent::create($attributes, $options);
    }

    public function update(array $attributes = [], array $options = []) {
        if (isset($attributes['new_password'])) {
            $attributes['password'] = Hash::make($attributes['new_password']);
        }
        parent::update($attributes, $options);
    }

}
