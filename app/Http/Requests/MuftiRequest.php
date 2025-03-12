<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MuftiRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true to allow validation
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'join_as' => 'required|in:scholar,lifecoach',
            'phone_number' => 'required',
            'fiqa' => 'required_if:join_as,scholar',

            //'degree' => 'required|array|min:1',
            //'degree.*.degree_title' => 'required',
            //'degree.*.institute_name' => 'required',
            //'degree.*.degree_startDate' => 'required',
            //'degree.*.is_present' => 'required|boolean',
            //'degree.*.degree_endDate' => 'nullable|required_if:is_present,false|prohibited_if:is_present,true',
            //'degree.*.degree_image' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp|max:3048',

            'work_experiences' => 'required|array|min:1',
            'work_experiences.*.company_name' => 'required',
            'work_experiences.*.experience_startDate' => 'required',
            'work_experiences.*.is_present' => 'required|boolean',
            'work_experiences.*.experience_endDate' => 'nullable|required_if:is_present,false|prohibited_if:is_present,true',

            'interest' => 'required|array|min:1',
            'interest.*' => 'required',
        ];
    }
}
