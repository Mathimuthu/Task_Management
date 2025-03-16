<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('landing'); 
})->name('landing');
Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', '\App\Http\Middleware\CheckPermission:1'])->group(function () {
    Route::get('/login', function () {
        return redirect()->route('landing'); 
    })->name('login');
    
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::resource('users', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('department', DepartmentController::class);
    Route::resource('tasks', TaskController::class);
    Route::get('/get-user-department', [TaskController::class, 'getUserDepartment'])->name('getUserDepartment');
    Route::post('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
});
Route::middleware(['auth'])->group(function () {
    // ✅ Define My Tasks Routes Correctly
    Route::get('mytasks', [TaskController::class, 'mytasks'])->name('mytasks');
    Route::post('mytasks', [TaskController::class, 'store'])->name('tasks.store'); // ✅ Add Store Route
    Route::get('mytasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');  
    Route::put('mytasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('mytasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // ✅ DELETE route
    Route::post('mytasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore'); // ✅ RESTORE route

    Route::get('/tasks/status-timeline/{task}', [TaskController::class, 'getTaskTimeline'])->name('tasks.status.timeline');
});
// Route::middleware(['auth', 'can:Admin'])->group(function () {
//     // Resource routes
//     Route::resource('users', UserController::class);
//     Route::resource('department', DepartmentController::class);
//     Route::resource('tasks', TaskController::class);

//     Route::post('/department/{id}/delete', [DepartmentController::class, 'destroy'])->name('department.destroy');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route::middleware('user')->group(function () {
//     Route::resource('users', UserController::class);
// });


require __DIR__ . '/auth.php';
