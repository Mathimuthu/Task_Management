<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;
use App\Models\TaskDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasksQuery = Task::select('tasks.*', 'departments.name as departmentname', 
                            'users1.name as username', 'users2.name as updatedby')
                ->leftJoin('departments', 'tasks.department_id', '=', 'departments.id')
                ->leftJoin('users as users1', 'tasks.employee_ids', '=', 'users1.id') 
                ->leftJoin('users as users2', 'tasks.updated_by', '=', 'users2.id'); 
            if (auth()->user()->hasRole(1)) {
                $tasksQuery = $tasksQuery->withTrashed();
            }
            $tasks = $tasksQuery->get();
    
            if (!auth()->user()->isAdmin()) {
                $tasks = $tasks->whereIn('id', [auth()->user()->id]);
            }
    
            return DataTables::of($tasks)
            ->addColumn('action', function ($task) {
                $timelineButton = '<button data-url="' . route('tasks.status.timeline', $task->id) . '"  
                                    class="ml-1 btn btn-sm btn-info viewTimelineBtn" 
                                    data-task-id="' . $task->id . '" title="View Timeline">
                                    <i class="fas fa-history"></i> 
                                 </button>';
        
                $editButton = '<button data-url="' . route('tasks.edit', $task->id) . '" class="btn btn-sm btn-primary edit-btn" title="Edit Task">
                                <i class="fas fa-edit"></i>
                              </button>';
        
                $deleteButton = '<button class="ml-1 btn btn-sm btn-danger delete-btn" data-url="' . route('tasks.destroy', $task->id) . '" data-id="' . $task->id . '" title="Delete Task">
                                 <i class="fas fa-trash-alt"></i>
                                </button>';
        
                $updateButton = '<button data-url="' . route('tasks.update', ['task' => $task->id]) . '"  
                                 class="ml-1 btn btn-sm btn-success updateStatusBtn" data-task-id="' . $task->id . '" data-description="'. $task->description .'" data-current-status="' . $task->status . '" title="Update Status">
                                 <i class="fas fa-sync-alt"></i>
                              </button>';
        
                $restoreButton = null;
                if ($task->deleted_at) {
                    if (auth()->user()->hasRole(1)) {
                        $restoreButton = '<button class="ml-1 btn btn-sm btn-warning restore-btn" data-url="' . route('tasks.restore', $task->id) . '" title="Restore Task">
                                          <i class="fas fa-undo"></i>
                                       </button>';
                        return $restoreButton . $timelineButton;
                    }
                }
        
                $returnData = "";
                if ($this->checkPermissionBasedRole('write tasks')) {
                    $returnData = $editButton . $updateButton;
                }
                if ($this->checkPermissionBasedRole('delete tasks')) {
                    $returnData = $returnData . $deleteButton;
                }
                if ($restoreButton) {
                    $returnData .= $restoreButton;
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
            'employee_ids' => 'required',
            'status' => 'required|in:Pending,In Progress,Completed',
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
                'employee_ids' => (int)$request->employee_ids,
                'status' => $request->status,
                'updated_by' => Auth::user()->id,
            ];
            if ($request->hasFile('upload_task')) {
                $uploadTask = $request->file('upload_task');
                $maxSize = 41943040; 
                $allowedExtensions = ['pdf', 'docx'];
                $fileExtension = $uploadTask->getClientOriginalExtension();
                $fileSize = $uploadTask->getSize();
            
                if ($fileSize > $maxSize) {
                    return response()->json(['error' => 'File is too large. Maximum allowed size is 40MB.'], 400);
                }
            
                if (in_array($fileExtension, $allowedExtensions)) {
                    $filename = time() . '.' . $fileExtension;
                    $uploadTask->move(public_path('upload_tasks'), $filename);
                    $taskData['upload_task'] = 'upload_tasks/' . $filename;
                } else {
                    return response()->json(['error' => 'Invalid file type. Only PDF and DOCX are allowed.'], 400);
                }
            }            
            if ($request->has('task_id') && !empty($request->task_id)) {
                $task = Task::find($request->task_id);
                if ($task) {
                    $task->update($taskData);
                    TaskDetail::create([
                        'task_id' => $task->id,
                        'meta_data' => json_encode([
                            'task_details' => [
                                'task_module' => 'Task Updated',
                                'title' => $request->title,
                                'description' => $request->description,
                                'priority' => $request->priority,
                                'date' => $request->assign_date,
                                'deadline' => $request->deadline,
                                'department_id' => $request->department_id,
                                'role_id' => $request->role_id,
                                'employee_ids' => $request->employee_ids,
                                'status' => $request->status,
                                'updated_by' => auth()->user()->id
                            ]
                            ])
                    ]);
                }
                return response()->json(['success' => 1, 'msg' => "Task Updated Successfully"]);
            } else {
                $task = Task::create($taskData);
                TaskDetail::create([
                    'task_id' => $task->id,
                    'meta_data' => json_encode([
                        'task_details' => [
                            'task_module' => 'Task Created',
                            'title' => $request->title,
                            'description' => $request->description,
                            'priority' => $request->priority,
                            'date' => $request->assign_date,
                            'deadline' => $request->deadline,
                            'department_id' => $request->department_id,
                            'role_id' => $request->role_id,
                            'employee_ids' => $request->employee_ids,
                            'status' => $request->status,
                            'updated_by' => auth()->user()->id
                        ]
                        ])
                ]);                
                return response()->json([
                    'success' => 1,
                    'msg' => 'Task Created Successfully'
                ]);
            }
        } catch (\Exception $e) {
            return ['success' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function getUserDepartment(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $user = User::find($employeeId);

        if ($user) {
            return response()->json([
                'success' => true,
                'department_id' => $user->department_id,  
            ]);
        }

        return response()->json(['success' => false]);
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
        // Update task status and description
        $task->update([
            'status' => $request->status,
            'description' => $request->description
        ]);
        TaskDetail::create([
            'task_id' => $task->id,
            'meta_data' => json_encode([
                'task_details' => [
                    'task_module' => 'Change Status',
                    'status' => $request->status,
                    'description' => $request->description,
                    'updated_by' => auth()->user()->id,
                ]
            ]),                
        ]);        

        return ['success' => 1, 'msg' => "Task Updated Successfully"];
    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete(); 
        TaskDetail::create([
            'task_id' => $task->id,
            'meta_data' => json_encode([
                'task_details' => [
                    'task_module' => 'Task Deleted',
                    'updated_by' => auth()->user()->id
                ]
                ])
        ]);        
        return redirect()->back()->with('success', 'Task deleted successfully');
    }

    public function getTaskTimeline($taskId)
    {
        // Fetch task details from the database
        $taskDetails = TaskDetail::where('task_id', $taskId)
            ->orderBy('updated_at', 'ASC')
            ->get();
    
        if ($taskDetails->isEmpty()) {
            return response()->json([
                'success' => false, 
                'html' => '<li>No status updates available.</li>'
            ]);
        }
    
        // Start constructing the HTML content for the timeline
        $timelineHtml = '';
    
        foreach ($taskDetails as $detail) {
            // Decode the meta data to extract task details
            $metaData = json_decode($detail->meta_data, true);
            $taskDetails = $metaData['task_details'] ?? [];
    
            // Build the timeline item for each task update
            $timelineHtml .= '<li class="timeline-item">';
            $timelineHtml .= '<div class="timeline-icon bg-info status-date"><i class="fas fa-history"></i> ' . $detail->updated_at->format('d-m-Y H:i:s') . ' </div>';
            $timelineHtml .= '<div class="timeline-content">';
            // $timelineHtml .= '<div class="timeline-header">';
            // $timelineHtml .= '<span class="status-date">' . $detail->updated_at->format('Y-m-d H:i:s') . '</span>';
            // $timelineHtml .= '<div class="task-user">';
            // $timelineHtml .= '<span class="user-name">' . (User::find($taskDetails['updated_by']) ? User::find($taskDetails['updated_by'])->name : 'Unknown') . '</span>';
            // $timelineHtml .= '</div></div>';
    
            $timelineHtml .= '<div class="timeline-body">';
            if (isset($taskDetails['updated_by'])) {
                $timelineHtml .= '<p><strong>Updated By:</strong> ' . (User::find($taskDetails['updated_by']) ? User::find($taskDetails['updated_by'])->name : 'Unknown') . '</p>';
            }
            if (isset($taskDetails['task_module'])) {
                $timelineHtml .= '<p><strong>Task Module:</strong> ' . $taskDetails['task_module'] . '</p>';
            }
            if (isset($taskDetails['title'])) {
                $timelineHtml .= '<p><strong>Title:</strong> ' . $taskDetails['title'] . '</p>';
            }
            if (isset($taskDetails['status'])) {
                $timelineHtml .= '<p><strong>Status:</strong> ' . $taskDetails['status'] . '</p>';
            }
            if (isset($taskDetails['description'])) {
                $timelineHtml .= '<p><strong>Description:</strong> ' . $taskDetails['description'] . '</p>';
            }
            if (isset($taskDetails['priority'])) {
                $timelineHtml .= '<p><strong>Priority:</strong> ' . $taskDetails['priority'] . '</p>';
            }
            if (isset($taskDetails['department_id'])) {
                $department = Department::find($taskDetails['department_id']);
                $timelineHtml .= '<p><strong>Department:</strong> ' . ($department ? $department->name : 'N/A') . '</p>';
            }
            if (isset($taskDetails['employee_ids'])) {
                $employees = User::whereIn('id', explode(',', $taskDetails['employee_ids']))->pluck('name')->toArray();
                $timelineHtml .= '<p><strong>Assigned Employee(s):</strong> ' . implode(', ', $employees) . '</p>';
            }
            $timelineHtml .= '</div></div></li>';
        }
    
        return response()->json([
            'success' => true,
            'html' => $timelineHtml
        ]);
    }
    
    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $task->restore();

        return response()->json(['success' => true, 'message' => 'Task restored successfully']);
    }
}
