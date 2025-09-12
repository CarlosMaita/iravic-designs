<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'is_superadmin',
        'is_employee',
    ];

    protected $casts = [
        'is_superadmin' => 'boolean',
        'is_employee' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
