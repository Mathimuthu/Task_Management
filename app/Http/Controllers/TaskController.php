<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::select('tasks.*', 'departments.name as departmentname')
                ->leftJoin('departments', 'tasks.department_id', '=', 'departments.id')
                ->get()
                ->map(function ($task) {
                    // Decode employee_ids JSON field
                    $employeeIds = json_decode($task->employee_ids, true);

                    // Fetch user names
                    $employees = User::whereIn('id', $employeeIds)->pluck('name')->toArray();

                    // Add employee names as a string (comma-separated)
                    $task->employee_names = implode(', ', $employees);

                    return $task;
                });


            return DataTables::of($tasks)
                ->addColumn('action', function ($task) {
                    $editButton = '<button data-url="' . route('tasks.edit', $task->id) . '" class="btn btn-sm btn-primary edit-btn">Edit</button>';
                    $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $task->id . '">Delete</button>';
                    $returnData = "";
                    if ($this->checkPermissionBasedRole('write tasks')) {
                        $returnData = $editButton;
                    }
                    if ($this->checkPermissionBasedRole('delete tasks')) {
                        $returnData = $returnData . $deleteButton;
                    }
                    return $returnData;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $departments = Department::where('status', 1)->get();
        $employees = User::where('status', 1)->get();
        $roles = Role::all();
        return view('tasks.index', compact('departments', 'employees', 'roles'));
    }

    /**
     * Search tasks.
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        $tasks = Task::select(['id', 'title', 'priority'])
            ->where('title', 'LIKE', "%{$query}%")
            ->get();
        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return true;
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'assign_date' => 'required|date',
            'deadline' => 'nullable|date',
            'department_id' => 'required|integer',
            // 'role_id' => 'required|integer',
            'employee_ids' => 'required|array',
            'status' => 'required|in:Pending,In Progress,Completed'
        ]);

        if ($validator->fails()) {
            return ['success' => 0, 'msg' => $validator->errors()->first()];
        }

        try {
            $taskData = [
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'date' => $request->assign_date,
                'deadline' => $request->deadline,
                'department_id' => $request->department_id,
                'role_id' => $request->role_id,
                'employee_ids' => json_encode($request->employee_ids),
                'status' => $request->status,
                'created_by' => Auth::user()->id,
            ];

            if ($request->has('task_id') && !empty($request->task_id)) {
                $task = Task::find($request->task_id);
                $task->update($taskData);
                return ['success' => 1, 'msg' => "Task Updated Successfully"];
            } else {
                Task::create($taskData);
                return ['success' => 1, 'msg' => "Task Created Successfully"];
            }
        } catch (\Exception $e) {
            return ['success' => 0, 'msg' => $e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        //
    }
}