<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_available' => $this->boolean('is_available'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'building' => ['required', 'string', 'max:255'],
            'is_available' => ['boolean'],
        ];
    }
}
