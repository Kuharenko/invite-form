<?php

namespace App\Http\Services\Invite;

class RecipientDTO
{
    public string $email;
    public string|null $full_name;
    public int $user_permission_id;

    public static function make(array $request): self
    {
        $dto = new self();
        $dto->email = $request['email'];
        $dto->full_name = $request['full_name'];
        $dto->user_permission_id = $request['user_permission_id'];
        return $dto;
    }
}