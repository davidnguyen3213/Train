<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Employees extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'weway_customer_number', 'surname', 'middle_name', 'name', 'mobile', 'work_number', 'home_number', 'date_of_birth', 'work_email', 'personal_email', 'pincode',
        'pincode_hint', 'joining_date', 'leaving_date', 'status', 'is_active', 'activation_code', 'bank_account', 'bank', 'employer_id', 'employee_number', 'department_id',
        'photo', 'id_number', 'address', 'personal_tax_code', 'facebook', 'linkedin', 'gender', 'marital_status', 'children', 'education', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pincode'
    ];

    /**
     * Define relationship table monthly_salary
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function monthlySalary()
    {
        return $this->hasMany('App\MonthlySalary', 'employee_id');
    }
}
