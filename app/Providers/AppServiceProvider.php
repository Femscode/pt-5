<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Role;
use App\Models\RolePermission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $perms = [
                'users' => ['view'=>false,'create'=>false,'edit'=>false,'delete'=>false],
                'marketplace' => ['view'=>false,'create'=>false,'edit'=>false,'delete'=>false],
                'events' => ['view'=>false,'create'=>false,'edit'=>false,'delete'=>false],
            ];
            $user = Auth::user();
            $isSuperadmin = false;
            if ($user && ($user->role ?? '') === 'superadmin') {
                $isSuperadmin = true;
                foreach ($perms as $key => $vals) {
                    $perms[$key] = ['view'=>true,'create'=>true,'edit'=>true,'delete'=>true];
                }
            } elseif ($user && ($user->role ?? '') === 'admin') {
                $slug = $user->role_type ?? null;
                if ($slug) {
                    $role = Role::where('slug', $slug)->first();
                    if ($role) {
                        $rps = RolePermission::where('role_id', $role->id)->get();
                        foreach ($rps as $rp) {
                            if (!isset($perms[$rp->resource])) continue;
                            $perms[$rp->resource]['view'] = (bool)$rp->can_view;
                            $perms[$rp->resource]['create'] = (bool)$rp->can_create;
                            $perms[$rp->resource]['edit'] = (bool)($rp->can_edit ?? false);
                            $perms[$rp->resource]['delete'] = (bool)$rp->can_delete;
                        }
                    }
                }
            }
            $view->with('adminPerms', $perms)->with('isSuperadmin', $isSuperadmin);
        });
    }
}
