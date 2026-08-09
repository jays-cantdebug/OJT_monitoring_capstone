<?php

namespace App\Http\Requests\Dean;

use App\Rules\NoCrlfCharacters;
use Illuminate\Foundation\Http\FormRequest;

class CreateStudentAccountRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', new NoCrlfCharacters],
        ];
    }
}
