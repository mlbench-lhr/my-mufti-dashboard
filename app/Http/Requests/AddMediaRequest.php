<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'temp_id' => 'required|exists:stages,id',
            'degree_title' => 'required',
            'institute_name' => 'required',
            'degree_startDate' => 'required',
            'degree_endDate'  => 'nullable|required_if:is_present,false',
            'is_present'      => 'required|in:true,false',
            'degree_image'   => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp|max:3048',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => false,
            'message' => $validator->errors()->first(),
        ], 200));
    }
}
