<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $eighteenYearsAgo = now()->subYears(18)->toDateString();
        $user = User::find($this->user_id);

        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:'.$eighteenYearsAgo],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',Rule::unique('users')->ignore($user->id)],
            'role' => ['required'],
        ];
    }
}
