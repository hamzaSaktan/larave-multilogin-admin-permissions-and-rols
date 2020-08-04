<?php

namespace App;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    protected $fillable = ['name','display_name','description'];

    public function scopeWhereRoleNot($query, $role_name){
        return $query->whereNotIn('name', (array)$role_name);
    }
}
