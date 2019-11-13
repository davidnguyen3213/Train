<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\UsesCustomErrorMessage;
use Illuminate\Foundation\Http\FormRequest;


class PayrollConfirmRequest extends FormRequest
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
            'employee_id' => 'required',
            'advance' => 'required|numeric|min:500000',
            'bank_account' => 'required',
            'year_month' => 'required',
        ];
    }
}
