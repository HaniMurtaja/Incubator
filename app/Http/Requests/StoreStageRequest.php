<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->hasAnyRole(['Admin', 'Mentor']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:255'],
            'stage_order' => ['required', 'integer', 'min:1'],
        ];
    }
}
