<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['email' => is_string($this->email) ? mb_strtolower(trim($this->email)) : $this->email]);
    }

    public function rules(): array
    {
        return ['email' => ['required', 'string', 'email', 'max:254'], 'name' => ['nullable', 'string', 'max:255']];
    }
}
