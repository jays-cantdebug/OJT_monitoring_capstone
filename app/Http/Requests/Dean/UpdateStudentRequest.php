<?php

namespace App\Http\Requests\Dean;

use App\Support\Geofence;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'is_verified' => ['nullable', 'boolean'],
            // A pin is both coordinates or neither - empty pair clears it.
            'company_latitude' => ['nullable', 'required_with:company_longitude', 'numeric', 'between:-90,90'],
            'company_longitude' => ['nullable', 'required_with:company_latitude', 'numeric', 'between:-180,180'],
            'geofence_radius_m' => ['sometimes', 'required', 'integer', 'between:'.Geofence::MIN_RADIUS_M.','.Geofence::MAX_RADIUS_M],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_latitude.required_with' => 'Drop a pin on the map to set the company location.',
            'company_longitude.required_with' => 'Drop a pin on the map to set the company location.',
        ];
    }
}
