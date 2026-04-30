<?php

namespace App\Http\Requests\Api;

use App\Models\Friendship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RespondToFriendRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    Friendship::STATUS_ACCEPTED,
                    Friendship::STATUS_DECLINED,
                ]),
            ],
        ];
    }
}
