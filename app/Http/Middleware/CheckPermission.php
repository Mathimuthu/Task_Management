<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $permissionsMap = [
            'index' => 'read',
            'create' => 'write',
            'store' => 'write',
            'edit' => 'write',
            'update' => 'write',
            'destroy' => 'delete',
            'show' => 'read',
            'restore' => 'write'
        ];

        // Get the current route name (e.g., "users.index")
        $routeName = Route::currentRouteName();
        $routeParts = explode('.', $routeName);

        if (count($routeParts) < 2) {
            return $next($request); // If route is not in expected format, allow access
        }

        // Extract module and action
        $module = $routeParts[0]; // Example: "users"
        $action = $routeParts[1]; // Example: "index"

        // Get the mapped permission name
        $permission = isset($permissionsMap[$action]) ? "{$permissionsMap[$action]} $module" : null;
        // dd($permission);
        $role = \Spatie\Permission\Models\Role::find(auth()->user()->role);
        if (!auth()->user() || !$role->hasPermissionTo($permission)) {
            abort(401, 'Not Found.');
        }
        return $next($request);
    }
}
