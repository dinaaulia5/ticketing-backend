
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\SubsystemController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login, Register, Logout, Password Reset, dll.
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        'dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->names('users');

    Route::resource('employees', UserController::class)
        ->names('employees');


    /*
    |--------------------------------------------------------------------------
    | Access Management
    |--------------------------------------------------------------------------
    */

    Route::resource('roles', RoleController::class)
        ->names('roles');

    Route::resource('permissions', UserController::class)
        ->names('permissions');


    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    */

    Route::get(
        'roles/{role}/permissions',
        [RolePermissionController::class, 'permission']
    )->name('roles.permissions');

    Route::put(
        'roles/{role}/permissions',
        [RolePermissionController::class, 'update']
    )->name('roles.permissions.update');

    /*
    |--------------------------------------------------------------------------
    | Mater 
    |--------------------------------------------------------------------------
    */

    Route::resource('categories', CategoryController::class)->names('categories');
    Route::resource('subsystems', SubsystemController::class)->names('subsystems');
});
