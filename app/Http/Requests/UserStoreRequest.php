<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserStoreRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $eighteenYearsAgo = now()->subYears(18)->toDateString();

        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)],
            'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:'.$eighteenYearsAgo],
            'password' => ['confirmed', Rules\Password::defaults()],
        ];
    }

   /* public function rules(): array
    {
        $rules = parent::rules();
        $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        return $rules;
    }*/

}
