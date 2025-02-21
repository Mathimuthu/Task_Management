<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;
use App\Models\TaskDetails;
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

            if (!auth()->user()->isAdmin()) {
                $tasks = $tasks->whereIn('id', auth()->user()->id);
            }

            return DataTables::of($tasks)
                ->addColumn('action', function ($task) {
                    $timelineButton = '<button data-url="' . route('tasks.status.timeline', $task->id) . '"  
                        class="ml-1 btn btn-sm btn-info viewTimelineBtn" 
                        data-task-id="' . $task->id . '">
                        <i class="fas fa-history"></i> History
                    </button>';
                    $editButton = '<button data-url="' . route('tasks.edit', $task->id) . '" class="btn btn-sm btn-primary edit-btn">Edit</button>';
                    $deleteButton = '<button class="ml-1 btn btn-sm btn-danger delete-btn" data-id="' . $task->id . '">Delete</button>';
                    $updateButton = '<button 
    data-url="' . route('tasks.update', ['task' => $task->id]) . '"  
    class="ml-1 btn btn-sm btn-success updateStatusBtn" 
    data-task-id="' . $task->id . '" 
    data-current-status="' . $task->status . '">
    Update Status
</button>';

                    $returnData = "";
                    if ($this->checkPermissionBasedRole('write tasks')) {
                        $returnData = $editButton . $updateButton;
                    }
                    if ($this->checkPermissionBasedRole('delete tasks')) {
                        $returnData = $returnData . $deleteButton;
                    }
                    return $returnData . $timelineButton;
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
                $task = Task::create($taskData);
                // Insert into task_details table
                TaskDetails::create([
                    'task_id' => $task->id,
                    'pending_date' => $request->status === 'Pending' ? now() : null,
                    'inprogress_date' => $request->status === 'In Progress' ? now() : null,
                    'completed_date' => $request->status === 'Completed' ? now() : null,
                    'cancelled_date' => $request->status === 'Cancelled' ? now() : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
        $request->validate([
            'status' => 'required|string',
            'description' => 'required|string',
        ]);

        // Find the task by ID
        $task = Task::findOrFail($id);
        $taskDetails = TaskDetails::findOrFail($task->id);

        // Update task status and description
        $task->update([
            'status' => $request->status,
        ]);

        // Update or Insert in task_details
        $taskDetails->update(
            [
                'pending_date' => $request->status === 'Pending' ? now() : $taskDetails->pending_date,
                'inprogress_date' => $request->status === 'In Progress' ? now() : $taskDetails->inprogress_date,
                'completed_date' => $request->status === 'Completed' ? now() : $taskDetails->completed_date,
                'cancelled_date' => $request->status === 'Cancelled' ? now() : $taskDetails->cancelled_date,
                'in_progress_desc' => $request->status === 'In Progress' ? $request->description : $taskDetails->cancelled_date,
                'completed_desc' => $request->status === 'Completed' ? $request->description : $taskDetails->completed_desc,
                'cancelled_desc' => $request->status === 'Cancelled' ? $request->description : $taskDetails->cancelled_desc,
                'updated_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Task status updated successfully!');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getTaskTimeline($taskId)
    {
        // Fetch status updates from TaskDetail table
        $taskDetails = TaskDetails::where('task_id', $taskId)
            ->orderBy('updated_at', 'ASC') // Order by earliest first
            ->get();

        if ($taskDetails->isEmpty()) {
            return response()->json(['success' => false, 'html' => '<li>No status updates available.</li>']);
        }

        // Status mapping
        $statusMap = [
            'pending_date' => 'Pending',
            'inprogress_date' => 'In Progress',
            'completed_date' => 'Completed',
            'cancelled_date' => 'Cancelled'
        ];

        // Build the timeline HTML
        $timelineHtml = '<ul class="timeline-list">';

        foreach ($taskDetails as $detail) {
            // Iterate over each status and check if a date exists
            foreach ($statusMap as $dateField => $status) {
                if (!empty($detail->$dateField)) { // Only show statuses with valid dates
                    $timelineHtml .= '
                <li class="timeline-item">
                    <span class="status-date">' . date('Y-m-d H:i:s', strtotime($detail->$dateField)) . '</span>
                    <div class="status-desc"><strong>' . ucfirst($status) . '</strong></div>
                    <div>' . (!empty($detail->description) ? htmlspecialchars($detail->description, ENT_QUOTES, 'UTF-8') : 'No description') . '</div>
                </li>';
                }
            }
        }

        $timelineHtml .= '</ul>';

        return response()->json(['success' => true, 'html' => $timelineHtml]);
    }
}
