<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employers extends Model
{
    protected $fillable = [
        'id', 'code', 'company', 'vat_number', 'first_contact', 'fc_title', 'fc_number', 'second_contact', 'sc_title', 'sc_number',
        'address', 'tax_code', 'website', 'payroll_payment_date', 'advance_all_salary_date', 'advance_date_adjustment', 'fee_tariff',
        'company_status', 'payroll_status', 'advance_percentage', 'created_at', 'updated_at'
    ];

}

