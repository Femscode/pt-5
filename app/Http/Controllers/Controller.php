<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\RolePermission;

abstract class Controller
{
    protected function checkPermission(string $resource, string $action): void
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            return;
        }
        if (!$user) {
            abort(403);
        }
        if (($user->role ?? '') !== 'admin') {
            abort(403);
        }
        $slug = $user->role_type ?? null;
        if (!$slug) {
            abort(403);
        }
        $role = Role::where('slug', $slug)->first();
        if (!$role) {
            abort(403);
        }
        $perm = RolePermission::where('role_id', $role->id)->where('resource', $resource)->first();
        if (!$perm) {
            abort(403);
        }
        $allowed = false;
        if ($action === 'view') $allowed = (bool)$perm->can_view;
        if ($action === 'create') $allowed = (bool)$perm->can_create;
        if ($action === 'edit') $allowed = (bool)$perm->can_edit;
        if ($action === 'delete') $allowed = (bool)$perm->can_delete;
        if (!$allowed) {
            abort(403);
        }
    }
}
