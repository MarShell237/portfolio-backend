<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Repositories\UserRepository;

class RegisterRequest extends FormRequest
{
    public function __construct(public UserRepository $userRepository)
    {
        //
    }
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'          => ['required', 'min:5'],
            'photo'         => ['nullable', 'image', 'max:5120'],
            'remember_me'         => ['nullable', 'boolean']
        ];

        if ($this->isMethod('post')) {
            $rules['email'] = ['required', 'email', 'unique:users,email'];
            $rules['password'] = ['required', 'confirmed', 'min:8'];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $userId = $this->userRepository->connected()->id;

            $rules['email'] = [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ];
        }
        return $rules;
    }
}
