<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddEventRequest extends FormRequest
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

    public function messages() {
        return [
            'title.required' => 'Обязательно для заполнения',
            'start.required' => 'Обязательно для заполнения',
            'end.required' => 'Обязательно для заполнения',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|min:5|max:255',
            'start' => 'required',
            'end' => 'required',
        ];
    }
}
