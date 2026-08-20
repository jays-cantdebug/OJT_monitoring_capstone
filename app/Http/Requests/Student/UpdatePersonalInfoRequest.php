<?php

namespace App\Http\Requests\Student;

use App\Rules\NoCrlfCharacters;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'personal_email' => ['nullable', 'string', 'email', 'max:255', new NoCrlfCharacters],
            'address' => ['nullable', 'string', 'max:2000'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_contact' => ['nullable', 'string', 'max:50'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:50'],
        ];
    }
}
