<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(
                'users.id',
                'users.name',
                'users.mobile',
                'users.email',
                'users.registration_no',
                'roles.name as role_name', 
                DB::raw('GROUP_CONCAT(departments.name SEPARATOR ", ") as department_names')
            )
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('departments', 'users.id', '=', 'departments.manager_id')
            ->whereNot('users.id', auth()->user()->id)
            ->groupBy(
                'users.id', 
                'users.name', 
                'users.mobile', 
                'users.email', 
                'users.registration_no', 
                'roles.name'
            )
            ->get();

            return DataTables::of($users)
                ->addColumn('action', function ($product) {
                    $editButton = '<button data-url="' . route('users.edit', $product->id) . '" class="btn btn-sm btn-primary edit-btn">Edit</button>';
                    $deleteButton = '<button class="ml-1 btn btn-sm btn-danger delete-btn" data-id="' . $product->id . '">Delete</button>';
                    $returnData = "";
                    if ($this->checkPermissionBasedRole('write users')) {
                        $returnData = $editButton;
                    }
                    if ($this->checkPermissionBasedRole('delete users')) {
                        $returnData = $returnData . $deleteButton;
                    }
                    return $returnData;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $departments = Department::select("*")->where('status', 1)->get();
        $roles = Role::select("*")->get();
        $hasCreatepermissions = $this->checkPermissionBasedRole('write users');
        return view('users.index', compact('departments', 'roles', 'hasCreatepermissions'));
    }

    public function search(Request $request)
    {
        $query = $request->get('search');
        $items = User::select(['id', 'name', 'mobile'])->where('name', 'LIKE', "%{$query}%")->get();
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return true;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->checkPermissionBasedRole('write users')) {
            $output = array('success' => 0, 'msg' => "You don't have permission to create/update user");
        }
        $userId = $request->user_id ?? null; // Check if it's an update
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'mobile' => 'required|max:10',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId), // Ignore current user
            ],
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            $output = array('success' => 0, 'msg' => $validator->errors()->first());
            return $output;
        } else {
            try {
                $userData = [];
                $userData['name'] = $request->name;
                $userData['mobile'] = $request->mobile;
                $userData['email'] = $request->email;
                $userData['role'] = $request->role;
                $userData['registration_no'] = $request->registration_no;
                $userData['department_id'] = $request->department_id;
                $userData['created_by'] = \Illuminate\Support\Facades\Auth::user()->id;
                $userData['status'] = 1;

                if ($request->has('user_id') && !empty($request->user_id)) {
                    $user = User::find($request->user_id);
                    $role = Role::where('id', $request->role)->first();
                    if (!$user->hasRole($role->name)) {
                        $user->syncRoles($role);
                    }
                    $user->update($userData);
                    return array('success' => 1, 'msg' => "Employee Updated Successfully");
                } else {
                    $userData['password'] = bcrypt("12345678");
                    User::create($userData);
                    return array('success' => 1, 'msg' => "Employee Created Successfully");
                }
            } catch (\Exception $e) {
                return array('success' => 0, 'msg' => $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!$this->checkPermissionBasedRole('write users')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
