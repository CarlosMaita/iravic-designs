<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    protected $table = 'roles';

    # Relations
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    # Scopes
    public function scopeWhereNotName($query, $role_name)
    {
        return $query->where('name', '<>', $role_name);
    }

    public function scopeWhereEmployee($query)
    {
        return $query->where('is_employee', 1);
    }

    public function scopeWhereNotEmployee($query)
    {
        return $query->where('is_employee', 0);
    }

    public function scopeWhereNotSuperadmin($query)
    {
        return $query->where('is_superadmin', 0);
    }

    # Methods
    public function allowTo($permission)
    {
        return $this->permissions()->save($permission);
    }

    public function allowToMany($permissions)
    {
        return $this->permissions()->saveMany($permissions);
    }
}
