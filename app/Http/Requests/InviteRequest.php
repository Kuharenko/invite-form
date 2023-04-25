<?php

namespace App\Http\Requests;

use App\Rules\UserPermissionExists;
use Illuminate\Foundation\Http\FormRequest;

class InviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'message' => 'nullable|max:255',
            'recipients' => 'required|array|min:1',
            'recipients.*.email' => 'required|email',
            'recipients.*.full_name' => 'nullable|string|min:2|max:255',
            'recipients.*.user_permission_id' => ['required','integer', new UserPermissionExists()],
        ];
    }
}
