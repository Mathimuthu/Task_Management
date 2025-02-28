<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $usersQuery = User::select('users.*',              
                'roles.name as role_name',   
                'departments.name as department_names',                    
                'users.department_id'
            )
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')   
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')              
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id');

            // Apply the order by created_at in descending order by default
            $usersQuery = $usersQuery->orderByDesc('users.created_at'); 

            if (auth()->user()->hasRole(1)) {
                $usersQuery = $usersQuery->withTrashed();  // Include soft-deleted users if admin
            }

            if (!auth()->user()->isAdmin()) {
                $usersQuery = $usersQuery->where('users.id', auth()->user()->id); // Only show the logged-in user
            }

            // Apply sorting from DataTables if it's present
            if ($request->has('order')) {
                $columnIndex = $request->input('order.0.column');
                $columnName = $request->input('columns.' . $columnIndex . '.data');
                $direction = $request->input('order.0.dir'); // 'asc' or 'desc'

                $usersQuery = $usersQuery->orderBy($columnName, $direction);
            }

            $users = $usersQuery->get();

            return DataTables::of($users)
            ->addColumn('status', function ($user) {
                return $user->status ? 1 : 0; // Ensure status is 1 for active
            })     
            ->addColumn('action', function ($product) {
                $editButton = '<button data-url="' . route('users.edit', $product->id) . '" class="btn btn-sm edit-btn" title="Edit">
                                <i class="fas fa-edit" style="color:#293fa4"></i>
                            </button>';
                $viewButton = '<button class="ml-1 btn btn-sm view-btn" data-url="' . route('users.show', $product->id) . '" data-id="' . $product->id . '" title="View">
                                <i class="fas fa-eye" style="color:#0a94cd"></i>
                            </button>';
                $deleteButton = '<button class="ml-1 btn btn-sm delete-btn" data-url="' . route('users.destroy', $product->id) . '" data-id="' . $product->id . '" title="Delete">
                                    <i class="fas fa-trash-alt" style="color:red"></i>
                                </button>';
        
                $restoreButton = "";
                if ($product->deleted_at && auth()->user()->hasRole(1)) {
                    $restoreButton = '<button class="ml-1 btn btn-sm restore-btn" data-url="' . route('users.restore', $product->id) . '" title="Restore">
                                        <i class="fas fa-undo" style="color:grey"></i> 
                                    </button>';
                }
            
                // Mobile View: Three-dot menu (⋯) with only text options
                $mobileMenu = '<div class="dropdown d-block d-sm-none">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton-' . $product->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ⋯
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-' . $product->id . '">';
                
                if ($this->checkPermissionBasedRole('write users')) {
                    $mobileMenu .= '<a class="dropdown-item edit-btn" href="#" data-url="' . route('users.edit', $product->id) . '">Edit</a>';
                }
                if ($this->checkPermissionBasedRole('delete users') && !$product->deleted_at) {
                    $mobileMenu .= '<a class="dropdown-item delete-btn" href="#" data-url="' . route('users.destroy', $product->id) . '" data-id="' . $product->id . '">Delete</a>';
                }
                if ($this->checkPermissionBasedRole('read users')) {
                    $mobileMenu .= '<a class="dropdown-item view-btn" href="#" data-url="' . route('users.show', $product->id) . '" data-id="' . $product->id . '">View</a>';
                }
                if ($restoreButton) {
                    $mobileMenu .= '<a class="dropdown-item restore-btn" href="#" data-url="' . route('users.restore', $product->id) . '">Restore</a>';
                }
                $mobileMenu .= '</div></div>';
        
                // Desktop View: Full buttons (TEXT ONLY, NO ICONS)
                $desktopMenu = '<div class="d-none d-sm-block">' . $editButton  . $deleteButton . $viewButton . $restoreButton . '</div>';
        
                return $desktopMenu . $mobileMenu;
            })            
            ->rawColumns(['status','action'])
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
                $department = Department::find($request->department_id);              
                $userData = [];
                $userData['name'] = $request->name;
                $userData['mobile'] = $request->mobile;
                $userData['email'] = $request->email;
                $userData['address'] = $request->address;
                $userData['dob'] = $request->dob;
                $userData['blood_group'] = $request->blood_group;
                $userData['role'] = $request->role;
                $userData['registration_no'] = $request->registration_no;
                $userData['department_id'] = $request->department_id;
                $userData['created_by'] =  Auth::user()->id;
                $userData['status'] = 1;
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $filename = time() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('employee'), $filename);
                    $userData['photo'] = 'employee/' . $filename; 
                }                
                if ($request->has('user_id') && !empty($request->user_id)) {
                    $user = User::find($request->user_id);
                    $role = Role::where('id', $request->role)->first();
                    if (!$user->hasRole($role->name)) {
                        $user->syncRoles($role);
                    }
                    if ($request->has('department_id')) {
                        // Get existing managers of the department
                        $existingDepartment = Department::find($request->department_id);
                        $currentManagers = $existingDepartment->manager_id ? json_decode($existingDepartment->manager_id) : [];
                        // Avoid adding the same user multiple times
                        if (!in_array($user->id, $currentManagers)) {
                            $currentManagers[] = $user->id; // Add new user to the list
                        }
                        $existingDepartment->manager_id = json_encode($currentManagers);
                        $existingDepartment->save();
                    } 
                    $user->update($userData);
                    return array('success' => 1, 'msg' => "Employee Updated Successfully");
                } else {
                    $userData['password'] = bcrypt("12345678");
                    $user = User::create($userData);
                    if ($department) {
                        // Get current managers, add new user
                        $currentManagers = $department->manager_id ? json_decode($department->manager_id) : [];
                        if (!in_array($user->id, $currentManagers)) {
                            $currentManagers[] = $user->id;
                        }
                        // Update the department manager list
                        $department->manager_id = json_encode($currentManagers);
                        $department->save();
                    }
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
    public function show($id)
    {
        $user = User::select(
            'users.id',
            'users.name',
            'users.registration_no',
            'users.mobile',
            'users.email',
            'users.status',
            'users.dob',
            'users.blood_group',
            'users.address',
            'roles.name as role_name',
            'users.photo',
            DB::raw('GROUP_CONCAT(departments.name SEPARATOR ", ") as department_names'),
            'users1.name as createdby'
        )
        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
        ->leftJoin('users as users1', 'users.created_by', '=', 'users1.id')
        ->where('users.id', $id)
        ->groupBy(
            'users.id', 
            'users.name', 
            'users.registration_no', 
            'users.mobile', 
            'users.email', 
            'users.status',
            'users.dob',
            'users.blood_group',
            'users.address',
            'roles.name',
            'users.photo',
            'users1.name'
        )
        ->first(); 

        return response()->json($user);
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);
    
        // Find the user by ID
        $user = User::findOrFail($id);
    
        // Update status
        $user->update(['status' => $request->status]);
    
        return response()->json(['message' => 'User status updated successfully!']);
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json(['success' => true, 'message' => 'User restored successfully']);
    }

}
