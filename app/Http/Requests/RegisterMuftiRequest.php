<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterMuftiRequest extends FormRequest
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
            'degree.*.degree_title' => 'required',
            'degree.*.institute_name' => 'required',
            'degree.*.degree_startDate' => 'required',
            'degree.*.is_present' => 'required|in:true,false',
            'degree.*.degree_endDate' => 'nullable|required_if:degree.*.is_present,false|prohibited_if:degree.*.is_present,true|date',
            'degree.*.degree_image' => 'required|file|image|mimes:jpg,png,jpeg|max:2048',
            'work_experiences.*.company_name' => 'required',
            'work_experiences.*.experience_startDate' => 'required',
            'work_experiences.*.is_present' => 'required|in:true,false',
            'work_experiences.*.experience_endDate' => 'nullable|required_if:work_experiences.*.is_present,false|prohibited_if:work_experiences.*.is_present,true|date',
            'interest.*' => 'required',
        ];
    }
}
