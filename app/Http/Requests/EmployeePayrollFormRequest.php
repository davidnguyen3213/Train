<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class EmployeePayrollFormRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year_month' => 'required',
            'contractual_salary' => 'required|numeric',
            'net_salary' => 'required|numeric',
            'standard_working_days' => 'required|numeric',
            'working_day_adjustment' => 'required|numeric',
            'actual_working_days' => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
//    public function messages()
//    {
//
//    }
}
