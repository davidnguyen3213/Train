<?php

namespace App\Repository\Contracts;

use App\Repository;

interface DepartmentsInterface extends RepositoryInterface
{
    public function searchWithDepartment(int $id_employer, int $limit, int $offset, array $searchCondition, array $orderBy);
    public function countDepartments(int $id_employer, array $searchCondition);
}