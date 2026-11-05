<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\RoleResource;
use App\Models\Permission;
use App\Supports\Pages\User\RolePage;
use Inertia\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $roles = Permission::query()
            ->select(['id', 'name', 'guard_name'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('User/Role/Index', [
            'pageSettings' => fn() => RolePage::index(),
            'roles' => fn() => RoleResource::collection($roles)->additional([
                'meta' => [
                    'has_pages' => $roles->hasPages(),
                ],
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],
            'items' => fn() => RolePage::breadcrumbs()
        ]);
    }
}
