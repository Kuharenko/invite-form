<?php

namespace App\Http\Services\Invite;

class InviteDTO
{
    public string|null $message = null;
    /**
     * @var RecipientDTO[] $recipients
     */
    public array $recipients = [];

    public static function make(array $request): self
    {
        $dto = new self();
        $dto->message = $request['message'];

        $recipients = [];
        foreach ($request['recipients'] as $recipient) {
            $recipients[] = RecipientDTO::make($recipient);
        }

        $dto->recipients = $recipients;
        return $dto;
    }
}