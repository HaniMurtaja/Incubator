<?php

namespace App\Http\Requests;

use App\Support\Statuses\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectDecisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'decision' => ['required', 'in:'.ProjectStatus::ACCEPTED.','.ProjectStatus::REJECTED],
            'decision_notes' => ['nullable', 'string'],
            'mentor_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
