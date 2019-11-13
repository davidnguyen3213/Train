<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts\EmployeesInterface as EmployeesInterface;
use Illuminate\Support\Facades\DB;

class EmployeesRepository extends BaseRepository implements EmployeesInterface
{

    protected function model()
    {
        return \App\Employees::class;
    }

    protected function getRules()
    {
        return \App\Employees::rules;
    }

    /**
     * @param array $searchCondition
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @return array|\Illuminate\Support\Collection
     */
    public function searchWithEmployees(array $searchCondition = [], int $limit = 0, int $offset = 0, array $orderBy = [])
    {
        $requestEmployeeModel = DB::table('employees as e')
            ->leftjoin("employers as er", "e.employer_id", '=', "er.id")
            ->leftjoin("departments as d", "e.department_id", '=', "d.id");

        // table employees
        $requestEmployeeStatus = isset($searchCondition["employee_status"]) ? $searchCondition["employee_status"] : null;
        $requestEmployeeName = isset($searchCondition["employee_name"]) ? $searchCondition["employee_name"] : null;
        $requestEmployeePhone = isset($searchCondition["employee_phone"]) ? $searchCondition["employee_phone"] : null;
        $requestEmployeeMail = isset($searchCondition["employee_mail"]) ? $searchCondition["employee_mail"] : null;
        $requestEmployeeEmployer = isset($searchCondition["employee_employer"]) ? $searchCondition["employee_employer"] : null;

        if ($requestEmployeeName != null) {
            $requestEmployeeModel = $requestEmployeeModel->where(function ($query) use ($requestEmployeeName) {
                $query->where('e.name', "like", "%{$requestEmployeeName}%")
                    ->orWhere('e.surname', "like", "%{$requestEmployeeName}%")
                    ->orWhere('e.middle_name', "like", "%{$requestEmployeeName}%")
                    ->orWhereRaw('IF(ISNULL(e.middle_name), CONCAT(e.surname, " ", e.name), CONCAT(e.surname, " ", e.middle_name, " ", e.name)) like ' . '"%' . $requestEmployeeName . '%"');
            });
        }

        if ($requestEmployeePhone != null) {
            $requestEmployeeModel = $requestEmployeeModel->where('e.mobile', '=', $requestEmployeePhone);
        }

        if ($requestEmployeeMail != null) {
            $requestEmployeeModel = $requestEmployeeModel->where(function ($query) use ($requestEmployeeMail) {
                $query->where('e.work_email', "like", "%{$requestEmployeeMail}%")
                    ->orWhere('e.personal_email', "like", "%{$requestEmployeeMail}%");
            });
        }

        if ($requestEmployeeEmployer != null) {
            $requestEmployeeModel = $requestEmployeeModel->where('er.company', 'like', "%{$requestEmployeeEmployer}%");
        }

        if ($requestEmployeeStatus != null) {
            $requestEmployeeModel = $requestEmployeeModel->where('e.status', '=', $requestEmployeeStatus);
        }


        if ($offset) {
            $requestEmployeeModel = $requestEmployeeModel->offset($offset);
        }

        if ($limit) {
            $requestEmployeeModel = $requestEmployeeModel->limit($limit);
        }

        if ($orderBy) {
            $requestEmployeeModel->orderBy($orderBy[0], $orderBy[1]);
        }

        $results = $requestEmployeeModel
            ->select(['e.*', 'er.company as employer_name', 'd.department as department'])
            ->get();

        if ($results && count($results) > 0) {
            return $results;
        } else {
            return array();
        }
    }

    /**
     * @param array $searchCondition
     */
    public function countSearchWithEmployees(array $searchCondition = [])
    {
        $requestEmployeeModel = DB::table('employees as e')
            ->leftjoin("employers as er", "e.employer_id", '=', "er.id")
            ->leftjoin("departments as d", "e.department_id", '=', "d.id");

        // table employees
        $requestEmployeeStatus = isset($searchCondition["employee_status"]) ? $searchCondition["employee_status"] : null;
        $requestEmployeeName = isset($searchCondition["employee_name"]) ? $searchCondition["employee_name"] : null;
        $requestEmployeePhone = isset($searchCondition["employee_phone"]) ? $searchCondition["employee_phone"] : null;
        $requestEmployeeMail = isset($searchCondition["employee_mail"]) ? $searchCondition["employee_mail"] : null;
        $requestEmployeeEmployer = isset($searchCondition["employee_employer"]) ? $searchCondition["employee_employer"] : null;

        if ($requestEmployeeStatus != null) {
            $requestEmployeeModel = $requestEmployeeModel->where('e.status', '=', $requestEmployeeStatus);
        }

        if ($requestEmployeeName != null) {
            $requestEmployeeModel = $requestEmployeeModel->where(function ($query) use ($requestEmployeeName) {
                $query->where('e.name', "like", "%{$requestEmployeeName}%")
                    ->orWhere('e.surname', "like", "%{$requestEmployeeName}%")
                    ->orWhere('e.middle_name', "like", "%{$requestEmployeeName}%");
            });
        }

        if ($requestEmployeePhone != null) {
            $requestEmployeeModel = $requestEmployeeModel->where('e.mobile', '=', $requestEmployeePhone);
        }

        if ($requestEmployeeMail != null) {
            $requestEmployeeModel = $requestEmployeeModel->where(function ($query) use ($requestEmployeeMail) {
                $query->where('e.work_email', "like", "%{$requestEmployeeMail}%")
                    ->orWhere('e.personal_email', "like", "%{$requestEmployeeMail}%");
            });
        }

        if ($requestEmployeeEmployer != null) {
            $requestEmployeeModel = $requestEmployeeModel->where('er.company', 'like', "%{$requestEmployeeEmployer}%");
        }

        return $requestEmployeeModel->getCountForPagination();
    }

}
