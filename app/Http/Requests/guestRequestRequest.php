<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class guestRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'response_status' => 'required',
            'talent' => 'required',
        ];
    }
}
