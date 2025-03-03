<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use function App\Helpers\checkPermissionBasedRole;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('permissions')->get();
            return DataTables::of($roles)
                ->addColumn('permissions', function ($role) {
                    return $role->permissions->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($role) {
                    $editButton = '<button class="mr-2 btn btn-sm edit-btn" data-id="' . $role->id . '" title="Edit"><i class="fa fa-edit" style="color:#293fa4"></i></button>';
                    $deleteButton = '<button class="btn btn-sm delete-btn" data-url="' . route('role.destroy', $role->id) . '" title="Delete"><i class="fa fa-trash" style="color:red"></i></button>';
                    $returnData = "";
                    if ($this->checkPermissionBasedRole('write role')) {
                        $returnData .=  $editButton;
                    }
                    if ($this->checkPermissionBasedRole('delete role')) {
                        $returnData .=  $deleteButton;
                    }
                    return $returnData;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $permissions = Permission::all();
        $hasCreatepermissions = $this->checkPermissionBasedRole('write role');
        return view('roles.index', compact('permissions', 'hasCreatepermissions'));
    }
    // Fetch role data for editing
    public function edit($id)
    {
        if (!$this->checkPermissionBasedRole('write role')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    // Update role
    public function update(Request $request, $id)
    {
        if (!$this->checkPermissionBasedRole('write role')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return response()->json(['success' => 'Role updated successfully']);
    }

    public function store(Request $request)
    {
        if (!$this->checkPermissionBasedRole('write role')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return response()->json(['success' => 'Role added successfully']);
    }

    public function destroy($id)
    {
        if (!$this->checkPermissionBasedRole('delete role')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['success' => 'Role deleted successfully']);
    }
}
