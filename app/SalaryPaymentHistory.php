<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryPaymentHistory extends Model
{
    protected $table = 'salary_payment_history';
    protected $fillable = [
        'employee_id', 'date', 'year_month', 'advance', 'payment', 'net_amount', 'description', 'created_at', 'updated_at'
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

