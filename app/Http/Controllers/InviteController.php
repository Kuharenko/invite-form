<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteRequest;
use App\Http\Services\Invite\InviteService;

class InviteController extends Controller
{
    public function __invoke(InviteRequest $request)
    {
        return InviteService::sendInvitations($request->all());
    }
}
