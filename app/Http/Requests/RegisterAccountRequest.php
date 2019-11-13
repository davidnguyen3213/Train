<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\UsesCustomErrorMessage;
use Illuminate\Foundation\Http\FormRequest;


class RegisterAccountRequest extends FormRequest
{
    use UsesCustomErrorMessage;

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
    public function rules()
    {
        return [
            'email' => 'required',
            'mobile' => 'required|mobile',
            'pincode' => 'required|digits:6',
            'pincode_hint' => 'required',
            'active_code' => 'required'
        ];
    }
}
