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
        $users = User::where('status', 1)->get();
        $usercount = $users->count();
        $task = Task::count();
        $mytask = Task::where('employee_ids',auth()->user()->id)->count(); 
    
        $taskCounts = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    
        $statuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
    
        foreach ($statuses as $status) {
            $taskCounts[$status] = $taskCounts[$status] ?? 0;
        }
    
        $maxCount = max($taskCounts) ?: 1;
    
        return view('home', compact('usercount', 'users', 'task', 'mytask', 'taskCounts', 'maxCount'));
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
