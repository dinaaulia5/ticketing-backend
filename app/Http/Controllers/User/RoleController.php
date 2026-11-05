<?php

namespace App\Http\Controllers\User;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RoleRequest;
use App\Http\Resources\User\PermissionResource;
use App\Http\Resources\User\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Supports\Pages\User\RolePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Throwable;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $roles = Role::query()
            ->select([
                'id',
                'name',
                'slug',
                'description',
                'is_active',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        $permissions = Permission::query()
            ->select([
                'id',
                'name',
                'guard_name',
            ])
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

            'permissions' => fn() => PermissionResource::collection($permissions)->additional([
                'meta' => [
                    'has_pages' => $permissions->hasPages(),
                ],
            ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],

            'items' => fn() => RolePage::breadcrumbs(),
        ]);
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
    public function store(RoleRequest $request): RedirectResponse
    {
        try {
            Role::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active'),
            ]);

            flashMessage(MessageType::CREATED->message('Peran'));
            return to_route('roles.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('roles.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role, RoleRequest $request): RedirectResponse
    {
        try {
            $role->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active'),

            ]);

            flashMessage(MessageType::UPDATED->message('Peran'));
            return to_route('roles.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('roles.index');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            flashMessage(MessageType::DELETE->message('Peran'));
            return to_route('roles.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('roles.index');
        }
    }
}
