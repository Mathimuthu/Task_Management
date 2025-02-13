<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function checkPermissionBasedRole($permission)
    {
        $role = \Spatie\Permission\Models\Role::find(auth()->user()->role);
        if (auth()->user() && $role->hasPermissionTo($permission)) {
            return true;
        }
        return false;
    }
}