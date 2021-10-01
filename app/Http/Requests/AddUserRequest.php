<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
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
            'login.required' => 'Обязательно для заполнения',
            'name.required' => 'Обязательно для заполнения',
            'lastname.required' => 'Обязательно для заполнения',
            'email.required' => 'Обязательно для заполнения',
            'password.required' => 'Обязательно для заполнения',
            'phone.required' => 'Обязательно для заполнения',
            'department_id.required' => 'Обязательно для заполнения',
            'position.required' => 'Обязательно для заполнения',
            'img.required' => 'Обязательно для заполнения',
        ];
    }

    public function rules()
    {
        return [
            'login' => 'required|min:5|max:255',
            'name' => 'required|min:3|max:255',
            'lastname' => 'required|min:3|max:255',
            'email' => 'required|min:5|max:255',
            'password' => 'required',
            'phone' => 'required|min:5|max:255',
            'department_id' => 'required',
            'position' => 'required|min:5|max:255',
            'img' => '',
        ];
    }
}
