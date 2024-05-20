<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ModalFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $eighteenYearsAgo = now()->subYears(18)->toDateString();

        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:'.$eighteenYearsAgo],
            'email' => ['required', 'string', 'email', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255'],
            'password' => [''],
            'role' => [''],
        ];

        if ($this->filled('user_id')) {
            $user = User::find($this->user_id);
            if ($user) {
                $rules['email'][] = Rule::unique('users')->ignore($user->id);
                $rules['username'][] = Rule::unique('users')->ignore($user->id);
            }
        }


        if (!$this->filled('user_id')) {
            $rules['password'][] = ['required', 'confirmed', Rules\Password::defaults()];
            $rules['email'][] = Rule::unique(User::class);
            $rules['username'][] = Rule::unique(User::class);
        }


        return $rules;
    }
/*    public function rules(): array
    {
        $eighteenYearsAgo = now()->subYears(18)->toDateString();

        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:'.$eighteenYearsAgo],
            'password' => ['confirmed', Rules\Password::defaults()],
        ];
    }*/
}
