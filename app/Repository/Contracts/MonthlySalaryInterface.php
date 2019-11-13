<?php

namespace App\Repository\Contracts;

use App\Repository;

interface MonthlySalaryInterface extends RepositoryInterface
{
    public function insertMonthSalary();
}