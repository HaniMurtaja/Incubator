<?php

namespace App\Http\Requests;

use App\Support\Statuses\EvaluationDecision;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->hasRole('Mentor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'decision' => ['required', 'in:'.implode(',', EvaluationDecision::all())],
            'comments' => ['nullable', 'string'],
        ];
    }
}
