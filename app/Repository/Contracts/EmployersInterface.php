<?php

namespace App\Repository\Contracts;

use App\Repository;

interface EmployersInterface extends RepositoryInterface
{
    public function searchWithEmployer(int $limit, int $offset, array $searchCondition, array $orderBy);
    public function countEmployer(array $searchCondition);
}