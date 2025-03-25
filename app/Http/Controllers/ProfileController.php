<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Task;

class ProfileController extends Controller
{
    public function index() {
        $loginuser = auth()->user();
        if ($loginuser->hasAnyRole('employee')) {
            $tasks = Task::where('employee_ids', $loginuser->id)->get();
    
            $mytask = $tasks->where('updated_by', $loginuser->id)->count();
    
            $task = $tasks->where('updated_by', '!=', $loginuser->id)->count();
            $taskCounts = Task::selectRaw('status, COUNT(*) as count')
            ->where('employee_ids', $loginuser->id)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            $users = collect();
        } else  if ($loginuser->hasAnyRole('admin')) {
            $tasks = Task::all();
            $task = $tasks->count();
            $mytask = Task::where('employee_ids', $loginuser->id)->where('updated_by',$loginuser->id)->count();
            $taskCounts = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();     
            $users = User::where('status', 1)->get();
        } else {
            $userIds = User::where('created_by', $loginuser->id)->pluck('id')->toArray(); 
            $tasks = Task::where(function ($query) use ($loginuser, $userIds) {
                $query->whereIn('tasks.updated_by', $userIds)
                    ->orWhere('tasks.updated_by', $loginuser->id);
            })->get();    
            $mytask = Task::where('updated_by', $loginuser->id)->where('employee_ids',$loginuser->id)->count();
            $taskCounts = Task::where(function ($query) use ($loginuser, $userIds) {
                $query->whereIn('tasks.updated_by', $userIds)
                      ->orWhere('tasks.updated_by', $loginuser->id);
            })
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();        
            $users = User::where('status', 1)->where('created_by',$loginuser->id)->get();
        }
        $task = $tasks->count();
        $usercount = $users->count();       
        $statuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
    
        foreach ($statuses as $status) {
            $taskCounts[$status] = $taskCounts[$status] ?? 0;
        }
    
        $maxCount = max($taskCounts) ?: 1;
        return view('home', compact('usercount', 'users', 'task', 'mytask', 'taskCounts', 'maxCount'));
    }
    public function filterUsersAndTasks(Request $request) {
        $selectedUserId = $request->user_id;
        if($selectedUserId == 'all'){
            $tasks = Task::all();
            $task = $tasks->count();
            $mytask = Task::where('employee_ids', auth()->user()->id)->where('updated_by',auth()->user()->id)->count();
            $taskCounts = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();     
            $users = User::where('status', 1)->get();
        }else{
            $selectedUserId = $request->user_id;
            $users = User::where('created_by', $selectedUserId)->where('status', 1)->get();
    
            $task = Task::where('updated_by', $selectedUserId)->count();
            $mytask = Task::where('employee_ids', $selectedUserId)->count(); 
        
            $taskCounts = Task::selectRaw('status, COUNT(*) as count')
                ->where('updated_by', $selectedUserId)
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        }   
    
        $statuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
        foreach ($statuses as $status) {
            $taskCounts[$status] = $taskCounts[$status] ?? 0;
        }
    
        return response()->json([
            'users' => $users,
            'task' => $task,
            'mytask' => $mytask,
            'taskCounts' => $taskCounts
        ]);
    }    
    
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
