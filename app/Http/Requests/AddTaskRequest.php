<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'title.required'    => 'Обязательно для заполнения',
            'text.required' => 'Обязательно для заполнения',
            'performers_id.required' => 'Обязательно для заполнения',
            'initiator_id.required' => 'Обязательно для заполнения',
            'priority_id.required' => 'Обязательно для заполнения',
            'startdate.required' => 'Обязательно для заполнения',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|min:5|max:255',
            'text' => 'required',
            'performers_id' => 'required',
            'initiator_id' => 'required',
            'priority_id' => 'required',
            'startdate' => 'required',
        ];
    }
}
