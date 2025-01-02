<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterMufti extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
            // 'fiqa' => 'required',
            'degree_title' => 'required',
            'institute_name' => 'required',
            'degree_startDate' => 'required',
            'degree_endDate' => 'required',
            'experience_startDate' => 'required',
            'experience_endDate' => 'required',
            'interest' => 'required',
            'degree_image' => 'required',
        ];
    }
}
