<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() && $this->user()->hasRole('Mentor');
    }

    public function rules()
    {
        return [
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }
}
