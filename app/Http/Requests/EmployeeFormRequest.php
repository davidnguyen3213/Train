<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class EmployeeFormRequest extends FormRequest
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
        $currentUserId = $this->request->get('employee_store_id');

        if ($currentUserId != null) {
            return [
                "employee_store_weway_customer_number" => ['required', 'unique:employees,weway_customer_number,' . $currentUserId],
                "employee_store_surname" => ['required'],
                "employee_store_name" => ['required'],
                "employee_store_mobile" => ['required', 'mobile', 'unique:employees,mobile,' . $currentUserId],
                "employee_store_work_number" => ['required', 'mobile'],
                "employee_store_home_number" => ['required', 'mobile'],
                "employee_store_date_of_birth" => ['required'],
                "employee_store_work_email" => ['required', 'unique:employees,work_email,' . $currentUserId],
                "employee_store_personal_email" => ['required'],
                "employee_store_joining_date" => ['required'],
                "employee_store_bank_account" => ['required'],
                "employee_store_bank" => ['required'],
                "employee_store_is_active" => ['required'],
                "employee_store_employer" => ['required']
            ];
        } else {
            return [
                "employee_store_weway_customer_number" => ['required', 'unique:employees,weway_customer_number'],
                "employee_store_surname" => ['required'],
                "employee_store_name" => ['required'],
                "employee_store_mobile" => ['required', 'mobile', 'unique:employees,mobile'],
                "employee_store_work_number" => ['required', 'mobile'],
                "employee_store_home_number" => ['required', 'mobile'],
                "employee_store_date_of_birth" => ['required'],
                "employee_store_work_email" => ['required', 'unique:employees,work_email'],
                "employee_store_personal_email" => ['required'],
                "employee_store_joining_date" => ['required'],
                "employee_store_bank_account" => ['required'],
                "employee_store_bank" => ['required'],
                "employee_store_is_active" => ['required'],
                "employee_store_employer" => ['required']
            ];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'employee_store_mobile.mobile' => 'The mobile number format is invalid',
            'employee_store_work_number.mobile' => 'The mobile number format is invalid',
            'employee_store_home_number.mobile' => 'The mobile number format is invalid',
            "employee_store_weway_customer_number.required" => "This field is required",
            "employee_store_surname.required" => "This field is required",
            "employee_store_name.required" => "This field is required",
            "employee_store_mobile.required" => "This field is required",
            "employee_store_work_number.required" => "This field is required",
            "employee_store_home_number.required" => "This field is required",
            "employee_store_date_of_birth.required" => "This field is required",
            "employee_store_work_email.required" => "This field is required",
            "employee_store_personal_email.required" => "This field is required",
            "employee_store_joining_date.required" => "This field is required",
            "employee_store_leaving_date.required" => "This field is required",
            "employee_store_bank_account.required" => "This field is required",
            "employee_store_bank.required" => "This field is required",
            "employee_store_is_active.required" => "This field is required",
            "employee_store_employer.required" => "This field is required",
            "employee_store_weway_customer_number.unique" => "The weway customer number has already been taken",
            "employee_store_mobile.unique" => "The mobile has already been taken",
            "employee_store_work_email.unique" => "The work email has already been taken",

        ];
    }
}
