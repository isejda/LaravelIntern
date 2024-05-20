<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;


class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:'.now()->subYears(18)->toDateString()],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
//            'image' => ['image', 'mimes:jpeg,png,jpg,gif'],
            'image' => [''],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function validateWithProfilePhoto(): array
    {
        $validated = $this->validator->validated();
//        $validated = $this->validated();

        if ($this->hasFile('image')){
//            $imagePath = $this->file('image')->store('profilePhoto', 'public');
            $validated['image'] = $this->file('image');
        }
        return $validated;
    }

}
