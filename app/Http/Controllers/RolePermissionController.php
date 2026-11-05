<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Supports\Pages\User\RolePage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;

class RolePermissionController extends Controller
{
    /**
     * Halaman Role & Permission
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
            ->paginate(request()->permission_load ?? 10)
            ->withQueryString();

        return inertia('User/Role/Index', [
            'pageSettings' => fn() => RolePage::index(),

            'roles' => fn() => $this->roleResource($roles),

            'permissions' => fn() => $this->permissionResource($permissions),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,

                'permission_page' => request()->permission_page ?? 1,
                'permission_load' => request()->permission_load ?? 10,
            ],

            'items' => fn() => RolePage::breadcrumbs(),
        ]);
    }

    /**
     * Mengambil permission untuk role tertentu.
     *
     * Digunakan oleh AccessTab ketika user memilih role.
     */
    public function permission(Role $role): JsonResponse
    {
        $permissions = Permission::query()
            ->select([
                'id',
                'name',
                'guard_name',
            ])
            ->orderBy('name')
            ->get();

        $rolePermissions = $role
            ->permissions()
            ->pluck('id');

        return response()->json([
            'permissions' => $permissions,
            'role_permissions' => $rolePermissions,
        ]);
    }

    /**
     * Menyimpan permission untuk role.
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'required',
                'string',
                'exists:permissions,id',
            ],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        $permissions = Permission::query()
            ->whereIn('id', $permissionIds)
            ->get();

        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Hak akses role berhasil diperbarui.',
            'permissions' => $role
                ->permissions()
                ->pluck('id')
                ->values(),
        ]);
    }

    /**
     * Resource Role.
     */
    private function roleResource($roles)
    {
        return \App\Http\Resources\User\RoleResource::collection($roles)
            ->additional([
                'meta' => [
                    'has_pages' => $roles->hasPages(),
                ],
            ]);
    }

    /**
     * Resource Permission.
     */
    private function permissionResource($permissions)
    {
        return \App\Http\Resources\User\PermissionResource::collection($permissions)
            ->additional([
                'meta' => [
                    'has_pages' => $permissions->hasPages(),
                ],
            ]);
    }
}
