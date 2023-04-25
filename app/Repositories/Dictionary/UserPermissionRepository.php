<?php

namespace App\Repositories\Dictionary;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Models\Dictionary\UserPermission;

class UserPermissionRepository
{
    public static function all(): Collection
    {
        return Cache::remember(UserPermission::class, 3600, function () {
            return UserPermission::all();
        });
    }
}