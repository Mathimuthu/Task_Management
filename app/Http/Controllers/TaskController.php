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

            $user = auth()->user();
            if ($user->hasAnyRole(['Admin', 'HR'])) {
                $tasksQuery = $tasksQuery->withTrashed();
            } elseif ($user->hasRole('Employee')) {
                // Employees only see their assigned tasks
                $tasksQuery->where('tasks.employee_ids', '=', $user->id);
            } else {
                // Other roles see tasks assigned to users they created
                $userIds = User::where('created_by', $user->id)->pluck('id')->toArray();
                $tasksQuery->whereIn('tasks.employee_ids', $userIds);
            }

            $tasks = $tasksQuery->get();    

            return DataTables::of($tasks)
            ->addColumn('status', function ($task) {
                return $task->status; // Return status as a simple value
            })
            ->addColumn('action', function ($task) {
                // Buttons for System (Desktop) View with Icons + Text
                $timelineButton = '<a href="#" data-url="' . route('tasks.status.timeline', $task->id) . '" class="ml-1 btn btn-sm viewTimelineBtn" data-task-id="' . $task->id . '" title="View Timeline">
                                       <i class="fas fa-history" style="color:#0a94cd"></i> 
                                   </a>';
        
                $editButton = '<a href="#" data-url="' . route('tasks.edit', $task->id) . '" class="btn btn-sm edit-btn" title="Edit Task">
                                   <i class="fas fa-edit" style="color:#293fa4"></i> 
                               </a>';
        
                $deleteButton = '<a href="#" class="ml-1 btn btn-sm delete-btn" data-url="' . route('tasks.destroy', $task->id) . '" data-id="' . $task->id . '" title="Delete Task">
                                    <i class="fas fa-trash-alt" style="color:red"></i> 
                                </a>';       
                $restoreButton = null;
                if ($task->deleted_at) {
                    if (auth()->user()->hasRole(1)) {
                        $restoreButton = '<a href="#" class="ml-1 btn btn-sm  restore-btn" data-url="' . route('tasks.restore', $task->id) . '" title="Restore Task">
                                              <i class="fas fa-undo" style="color:grey"></i> 
                                          </a>';
                        return $restoreButton . $timelineButton;
                    }
                }
        
                $returnData = "";
                if ($this->checkPermissionBasedRole('write tasks')) {
                    $returnData = $editButton ;
                }
                if ($this->checkPermissionBasedRole('delete tasks')) {
                    $returnData .= $deleteButton;
                }
                if ($restoreButton) {
                    $returnData .= $restoreButton;
                }
        
                // System (Desktop) View: Full buttons with icons + text
                $desktopMenu = '<div class="d-none d-sm-block">' . $returnData . $timelineButton . '</div>';
        
                // Mobile View: Three-dot (⋯) menu with only text options
                $mobileMenu = '<div class="dropdown d-block d-sm-none">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton-' . $task->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ⋯
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-' . $task->id . '">';
                if ($this->checkPermissionBasedRole('write tasks')) {
                    $mobileMenu .= '<a class="dropdown-item edit-btn" href="#" data-url="' . route('tasks.edit', $task->id) . '">Edit</a>';
                }
                if ($this->checkPermissionBasedRole('delete tasks')) {
                    $mobileMenu .= '<a class="dropdown-item delete-btn" href="#" data-url="' . route('tasks.destroy', $task->id) . '" data-id="' . $task->id . '">Delete</a>';
                }
                if ($restoreButton) {
                    $mobileMenu .= '<a class="dropdown-item restore-btn" href="#" data-url="' . route('tasks.restore', $task->id) . '">Restore</a>';
                }
                $mobileMenu .= '<a class="dropdown-item viewTimelineBtn" href="#" data-url="' . route('tasks.status.timeline', $task->id) . '" data-task-id="' . $task->id . '">View History</a>';
                $mobileMenu .= '</div></div>';
        
                return $desktopMenu . $mobileMenu;
            })
            ->rawColumns(['status','action'])
            ->make(true);             
        }
    
        $departments = Department::where('status', 1)->get();
        $loggedInUser = auth()->user();
        if ($loggedInUser->hasAnyRole(['Admin', 'HR'])) {
            $employees = User::where('status', 1)->get(); // Show all employees
        } elseif ($loggedInUser->hasRole('Employee')) {
            $employees = User::where('id', $loggedInUser->id)->where('status', 1)->get(); // Show only the logged-in employee
        } else {
            // Show users created by the logged-in user
            $employees = User::where('created_by', $loggedInUser->id)->where('status', 1)->get();
        }
        $roles = Role::all();
        return view('tasks.index', compact('departments', 'employees', 'roles'));
    }    
    public function mytasks(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::select(
                    'tasks.id',
                    'tasks.title',
                    'tasks.priority',
                    'tasks.date as date',  
                    'tasks.deadline',
                    'departments.name as departmentname',
                    'users1.name as username',
                    'users2.name as updatedby',
                    'tasks.status'
                )
                ->leftJoin('departments', 'tasks.department_id', '=', 'departments.id')
                ->leftJoin('users as users1', 'tasks.employee_ids', '=', 'users1.id') 
                ->leftJoin('users as users2', 'tasks.updated_by', '=', 'users2.id') 
                ->where('tasks.employee_ids', auth()->id());   
                if (auth()->user()->hasRole(1)) {
                    $tasks = $tasks->withTrashed();
                }
                $tasks = $tasks->get();
            return DataTables::of($tasks)
            ->addColumn('status', function ($task) {
                return $task->status; // Return status as a simple value
            })
            ->addColumn('action', function ($task) {
                // Buttons for System (Desktop) View with Icons + Text
                $timelineButton = '<a href="#" data-url="' . route('tasks.status.timeline', $task->id) . '" class="ml-1 btn btn-sm viewTimelineBtn" data-task-id="' . $task->id . '" title="View Timeline">
                                       <i class="fas fa-history" style="color:#0a94cd"></i> 
                                   </a>';
        
                $editButton = '<a href="#" data-url="' . route('tasks.edit', $task->id) . '" class="btn btn-sm edit-btn" title="Edit Task">
                                   <i class="fas fa-edit" style="color:#293fa4"></i> 
                               </a>';
        
                $deleteButton = '<a href="#" class="ml-1 btn btn-sm delete-btn" data-url="' . route('tasks.destroy', $task->id) . '" data-id="' . $task->id . '" title="Delete Task">
                                    <i class="fas fa-trash-alt" style="color:red"></i> 
                                </a>';
                $restoreButton = null;
                if ($task->deleted_at) {
                    if (auth()->user()->hasRole(1)) {
                        $restoreButton = '<a href="#" class="ml-1 btn btn-sm  restore-btn" data-url="' . route('tasks.restore', $task->id) . '" title="Restore Task">
                                              <i class="fas fa-undo" style="color:grey"></i> 
                                          </a>';
                        return $restoreButton . $timelineButton;
                    }
                }
        
                $returnData = "";
                if ($this->checkPermissionBasedRole('write tasks')) {
                    $returnData = $editButton ;
                }
                if ($this->checkPermissionBasedRole('delete tasks')) {
                    $returnData .= $deleteButton;
                }
                if ($restoreButton) {
                    $returnData .= $restoreButton;
                }
        
                // System (Desktop) View: Full buttons with icons + text
                $desktopMenu = '<div class="d-none d-sm-block">' . $returnData . $timelineButton . '</div>';
        
                // Mobile View: Three-dot (⋯) menu with only text options
                $mobileMenu = '<div class="dropdown d-block d-sm-none">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton-' . $task->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ⋯
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-' . $task->id . '">';
                if ($this->checkPermissionBasedRole('write tasks')) {
                    $mobileMenu .= '<a class="dropdown-item edit-btn" href="#" data-url="' . route('tasks.edit', $task->id) . '">Edit</a>';
                }
                if ($this->checkPermissionBasedRole('delete tasks')) {
                    $mobileMenu .= '<a class="dropdown-item delete-btn" href="#" data-url="' . route('tasks.destroy', $task->id) . '" data-id="' . $task->id . '">Delete</a>';
                }
                if ($restoreButton) {
                    $mobileMenu .= '<a class="dropdown-item restore-btn" href="#" data-url="' . route('tasks.restore', $task->id) . '">Restore</a>';
                }
                $mobileMenu .= '<a class="dropdown-item viewTimelineBtn" href="#" data-url="' . route('tasks.status.timeline', $task->id) . '" data-task-id="' . $task->id . '">View History</a>';
                $mobileMenu .= '</div></div>';
        
                return $desktopMenu . $mobileMenu;
            })
            ->rawColumns(['status','action'])
            ->make(true); 
        }
    
        // Load the view for non-AJAX requests
        $departments = Department::where('status', 1)->get();
        $employees = User::where('status', 1)->get();
        $roles = Role::all();
        return view('tasks.index1', compact('departments', 'employees', 'roles'));
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
                $allowedExtensions = ['pdf', 'docx','jpeg','jpg','webp'];
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Cancelled'
        ]);
    
        $task = Task::findOrFail($id);
        $task->update(['status' => $request->status]); 

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
    
        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully!',
            'status' => $task->status
        ]);   
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
        $taskDetails = TaskDetail::where('task_id', $taskId)
            ->orderBy('updated_at', 'DESC')
            ->get();

        if ($taskDetails->isEmpty()) {
            return response()->json([
                'success' => false, 
                'html' => '<li>No status updates available.</li>'
            ]);
        }

        $timelineHtml = '';

        foreach ($taskDetails as $detail) {
            $metaData = json_decode($detail->meta_data, true);
            $taskDetails = $metaData['task_details'] ?? [];

            $timelineHtml .= '<li class="timeline-item">';
            $timelineHtml .= '<div class="timeline-icon status-date"> ' . $detail->updated_at->format('d F Y, h:i A') . ' </div>';
            $timelineHtml .= '<div class="timeline-content">';
            $timelineHtml .= '<div class="timeline-body">';

            if (isset($taskDetails['task_module'])) {
                $timelineHtml .= '<p><strong>Task Action:</strong> ' . $taskDetails['task_module'] . '</p>';
            }
            if (isset($taskDetails['updated_by'])) {
                $user = User::find($taskDetails['updated_by']);
                $timelineHtml .= '<p><strong>Updated By:</strong> ' . ($user ? $user->name : 'Unknown') . '</p>';
            }
            if (isset($taskDetails['title'])) {
                $timelineHtml .= '<p><strong>Title:</strong> ' . $taskDetails['title'] . '</p>';
            }
            if (isset($taskDetails['status'])) {
                $timelineHtml .= '<p><strong>Status:</strong> ' . $taskDetails['status'] . '</p>';
            }
            if (isset($taskDetails['description']) && !empty($taskDetails['description'])) {
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
            if (isset($taskDetails['date'])) {
                $timelineHtml .= '<p><strong>Assign Date:</strong> ' . $taskDetails['date'] . '</p>';
            }
            if (isset($taskDetails['deadline'])) {
                $timelineHtml .= '<p><strong>Deadline:</strong> ' . $taskDetails['deadline'] . '</p>';
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
        TaskDetail::create([
            'task_id' => $task->id,
            'meta_data' => json_encode([
                'task_details' => [
                    'task_module' => 'Task Restored',
                    'updated_by' => auth()->user()->id
                ]
                ])
        ]);     
        return response()->json(['success' => true, 'message' => 'Task restored successfully']);
    }
}
