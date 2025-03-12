<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Department::select('departments.*', 'users.name as manager_name')
                ->leftJoin('users', 'users.id', '=', 'departments.manager_id')
                ->when($request->status, function ($query, $status) {
                    if ($status === 'Active') {
                        return $query->where('departments.status', 1);
                    } elseif ($status === 'Inactive') {
                        return $query->where('departments.status', 0);
                    }
                })    
                ->get();
    
                return DataTables::of($products)
                ->addColumn('action', function ($product) {
                    $editButton = '<button data-url="' . route('department.edit', $product->id) . '" data-id="' . $product->id . '" data-toggle="modal" data-target="#modalPurple" class="mr-2 btn btn-sm edit-btn" title="Edit">
                                        <i class="fas fa-edit" style="color:#293fa4"></i>
                                    </button>';
            
                    $deleteButton = '<button class="btn btn-sm delete-btn" data-url="' . route('department.destroy', $product->id) . '" data-id="' . $product->id . '" title="Delete">
                                        <i class="fas fa-trash-alt" style="color:red"></i>
                                    </button>';
            
                    $returnData = "";
            
                    if ($this->checkPermissionBasedRole('write department')) {
                        $returnData = $editButton;
                    }
                    if ($this->checkPermissionBasedRole('delete department')) {
                        $returnData .= $deleteButton;
                    }
            
                    // Full buttons for desktop view (hidden on small screens)
                    $desktopMenu = '<div class="d-none d-sm-block">' . $returnData . '</div>';
            
                    // Burger menu for mobile view (visible only on small screens)
                    $mobileMenu = '<div class="dropdown d-block d-sm-none">
                                    <button class="btn btn-sm" type="button" id="dropdownMenuButton-' . $product->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i> 
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-' . $product->id . '">';
                    if ($this->checkPermissionBasedRole('write department')) {
                        $mobileMenu .= '<a class="dropdown-item edit-btn" href="#" data-url="' . route('department.edit', $product->id) . '" data-id="' . $product->id . '" data-toggle="modal" data-target="#modalPurple">Edit</a>';
                    }
                    if ($this->checkPermissionBasedRole('delete department')) {
                        $mobileMenu .= '<a class="dropdown-item delete-btn" href="#" data-url="' . route('department.destroy', $product->id) . '" data-id="' . $product->id . '">Delete</a>';
                    }
                    $mobileMenu .= '</div></div>';
            
                    return $desktopMenu . $mobileMenu;
                })
                ->addColumn('manager_name', function ($product) {
                    // Check if manager_id is not null or empty
                    $managerIds = json_decode($product->manager_id, true); // Decode as an array
                    if (is_array($managerIds) && !empty($managerIds)) {
                        $managers = User::whereIn('id', $managerIds)->pluck('name')->toArray();
                        return implode(', ', $managers);
                    } else {
                        return 'No manager assigned'; // Return a default message if no manager
                    }
                })
                ->rawColumns(['action'])
                ->make(true);            
        }
    
        $employees = User::where('status', 1)->get();
        $hasCreatepermissions = $this->checkPermissionBasedRole('write department');
    
        return view('department.index', compact('employees', 'hasCreatepermissions'));
    }    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        try {
            if ($request->has('department_id') && !empty($request->department_id)) {
                $department = Department::find($request->department_id);
                if (!$department) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Department not found.',
                    ]);
                }
                $department->name = $request->name;
                $department->description = $request->description;
                $department->status = 1;
    
                if ($request->has('employee_id')) {
                    $managerIds = array_unique(array_map('intval', $request->employee_id));
    
                    foreach ($managerIds as $managerId) {
                        $user = User::find($managerId);
                        if ($user) {
                            $oldDepartmentId = $user->department_id;
    
                            if ($oldDepartmentId != $department->id) {
                                if ($oldDepartmentId) {
                                    $oldDepartment = Department::find($oldDepartmentId);
                                    if ($oldDepartment) {
                                        $oldManagerIds = json_decode($oldDepartment->manager_id, true);
                                        $oldManagerIds = array_filter($oldManagerIds, fn($id) => $id != $managerId);
                                        $oldDepartment->manager_id = json_encode(array_values($oldManagerIds));
                                        $oldDepartment->save();
                                    }
                                }
    
                                $user->department_id = $department->id;
                                $user->save();
                            }
                        }
                    }
    
                    $department->manager_id = json_encode(array_values($managerIds));
                } else {
                    $department->manager_id = json_encode([]); 
                }
    
                $department->save();
            } else {
                $department = Department::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'status' => 1,
                    'manager_id' => $request->has('employee_id') ? json_encode(array_map('intval', $request->employee_id)) : json_encode([]),
                ]);
                if ($request->has('employee_id') && !empty($request->employee_id)) {
                    $managerIds = array_unique(array_map('intval', $request->employee_id));

                    foreach ($managerIds as $managerId) {
                        $user = User::find($managerId);
                        if ($user) {
                            $oldDepartmentId = $user->department_id;

                            if ($oldDepartmentId && $oldDepartmentId != $department->id) {
                                $oldDepartment = Department::find($oldDepartmentId);
                                if ($oldDepartment) {
                                    $oldManagerIds = json_decode($oldDepartment->manager_id, true) ?? [];
                                    $oldManagerIds = array_filter($oldManagerIds, fn($id) => $id != $managerId);
                                    $oldDepartment->manager_id = json_encode(array_values($oldManagerIds));
                                    $oldDepartment->save();
                                }
                            }
                            $user->department_id = $department->id;
                            $user->save();
                        }
                    }

                    $department->manager_id = json_encode(array_values($managerIds));
                } else {
                    $department->manager_id = json_encode([]);
                }

                $department->save();
            }
    
            return response()->json([
                'success' => true,
                'msg' => 'Department saved successfully!',
                'redirect_url' => route('department.index'),
            ]);
            } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error: ' . $e->getMessage(),
            ]);
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
        $department = Department::find($id);
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!$this->checkPermissionBasedRole('write department')) {
            return response()->json(['error' => 'Permission denied']);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
        ]);
        if ($validator->fails()) {
            $output = array('success' => 0, 'msg' => $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput()->with('status', $output);
        }
    
        $departmentData = [];
        $departmentData['name'] = $request->name;
        $departmentData['status'] = 1;
        $departmentData['description'] = $request->description;
    
        $managerIds = $request->employee_id;
        $existingManagerIds = json_decode(Department::find($id)->manager_id ?? '[]');
    
        if ($existingManagerIds) {
            $managerIds = array_unique(array_merge($existingManagerIds, $managerIds)); // Merge old and new unique manager ids
        }
    
        $departmentData['manager_id'] = json_encode($managerIds); // Store as a JSON string
    
        $department = Department::find($id);
        $department->update($departmentData);
    
        foreach ($managerIds as $managerId) {
            $user = User::find($managerId);
            if ($user) {
                // Assign department_id to the user
                $user->department_id = $department->id;
                $user->save();
            }
        }
        return redirect()->route('department.index')->with('success', 'Department updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->checkPermissionBasedRole('delete department')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $department = Department::find($id);
        $department->update(['status' => 0]); 
        return redirect()->route('department.index')->with('success', 'Department deleted successfully!');
    }
}
