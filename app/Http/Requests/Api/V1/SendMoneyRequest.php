<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMoneyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_email' => [
                'required',
                'email',
                Rule::exists(User::class, 'email')->whereNot('id', $this->user()->id),
            ],
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],
            'reason' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function getRecipient(): User
    {
        return User::where('email', '=', $this->input('recipient_email'))->firstOrFail();
    }
}
