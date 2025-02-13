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
                ->leftJoin('users', 'users.id', '=', 'departments.manager_id');
            return DataTables::of($products)
                ->addColumn('action', function ($product) {

                    $editButton = '<button data-url="' . route('department.edit', $product->id) . '"data-id="' . $product->id . ' data-toggle="modal" data-target="#modalPurple" class="mr-2 btn btn-sm btn-primary edit-btn">Edit</button>';
                    $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" data-url="' . route('department.destroy', $product->id) . '" data-id="' . $product->id . '">Delete</button>';
                    $returnData = "";
                    if ($this->checkPermissionBasedRole('write department')) {
                        $returnData = $editButton;
                    }
                    if ($this->checkPermissionBasedRole('delete department')) {
                        $returnData = $returnData . $deleteButton;
                    }
                    return $returnData;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $employees = User::where('status', 1)->where('role', 2)->get();
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
        if (!$this->checkPermissionBasedRole('write department')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $request->validate([
            'name' => 'required|string|max:64',
        ]);
        $departmentData = [];
        $departmentData['name'] = $request->name;
        $departmentData['manager_id'] = $request->employee_id;
        $departmentData['description'] = $request->description;
        $departmentData['status'] = 1;

        if (isset($request->id) && !empty($request->id)) {
            $department = Department::find($request->id);
            $department->update($departmentData);
            return redirect()->route('department.index')->with('success', 'Department updated successfully!');
        } else {
            $department = Department::create($departmentData);
            return redirect()->route('department.index')->with('success', 'Department added successfully!');
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
        $departmentData['manager_id'] = $request->employee_id;
        $departmentData['description'] = $request->description;
        $departmentData['status'] = 1;

        if (isset($request->id) && !empty($request->id)) {
            $department = Department::find($request->id);
            $department->update($departmentData);
            return redirect()->route('department.index')->with('success', 'Department updated successfully!');
        } else {
            $department = Department::create($departmentData);
            return redirect()->route('department.index')->with('success', 'Department added successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->checkPermissionBasedRole('delete department')) {
            return response()->json(['error' => 'Permission denied']);
        }
        $departmentData = [];
        $departmentData['status'] = 0;
        $department = Department::find($id);
        $department->update($departmentData);
        return redirect()->route('department.index')->with('success', 'Department deleted successfully!');
    }
}
