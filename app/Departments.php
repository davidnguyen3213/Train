<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $fillable = [
        'id', 'employer_id', 'parent_division', 'department', 'head_of_department', 'hod_mobile', 'hod_office_number', 'hod_email', 'created_at', 'updated_at'
    ];


}

