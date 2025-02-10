<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'           => 'required',
            'image'             => 'required',
            'event_title'       => 'required',
            'event_category'    => 'required',
            'date'              => 'required',
            'duration'          => 'required',
            'location'          => 'required',
            'latitude'          => 'required',
            'longitude'         => 'required',
            'about'             => 'required',
            // 'question_end_time' => 'required',
        ];
    }
}
