<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $attributes = [
    ];

    // Relations
    public function boxes()
    {
        return $this->hasMany('App\Models\Box');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withTimestamps();
    }

    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    # Methods
    public function assignRole($role)
    {
        return $this->roles()->sync($role);
    }

    /**
     * Retorna boolean para verificar si el usuario tiene rol de administrador (Incluido superadmin)
     */
    public function isAdmin()
    {
        $roles_name = $this->roles->flatten()->pluck('name');
        return $roles_name->contains('superadmin') || $roles_name->contains('admin');
    }

    /**
     * Retorna boolean para verificar si el usuario tiene rol de Super Administrador
     */
    public function isSuperAdmin()
    {
        $roles_name = $this->roles->flatten()->pluck('name');
        return $roles_name->contains('superadmin') ;
    }
}
