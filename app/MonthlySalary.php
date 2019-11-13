<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    protected $table = 'monthly_salary';
    protected $fillable = [
        'id', 'employee_id', 'employer_id', 'employee_number', 'department_id', 'weway_customer_number', 'contractual_salary', 'net_salary',
        'standard_working_days', 'working_day_adjustment', 'actual_working_days', 'payroll_payment_date', 'advance_all_salary_date', 'advance_date_adjustment',
        'fee_tariff', 'year_month', 'created_at', 'updated_at'
    ];

    /**
     * Define relationship table Employees
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employees()
    {
        return $this->belongsto('App\Employees', 'employee_id', 'id');
    }

}

