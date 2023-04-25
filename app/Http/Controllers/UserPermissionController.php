<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserPermissionResource;
use App\Repositories\Dictionary\UserPermissionRepository;

class UserPermissionController extends Controller
{
    public function __invoke()
    {
        return UserPermissionResource::collection(
            UserPermissionRepository::all()
        );
    }
}
