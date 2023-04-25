<?php

namespace App\Http\Services\Invite;

use App\Mail\Invite;
use Illuminate\Support\Facades\Mail;

class InviteService
{
    private function __construct(private InviteDTO $inviteDTO)
    {

    }

    public static function sendInvitations(array $params): array
    {
        $dto = InviteDTO::make($params);
        $notificationAmount = (new InviteService($dto))->send();

        return ['message' => "Were sent $notificationAmount invites"];
    }

    private function send(): int
    {
        $message = $this->inviteDTO->message;
        $recipients = $this->inviteDTO->recipients;

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->later(
                now()->addMinute(),
                new Invite($recipient, $message)
            );
        }

        return count($recipients);
    }
}