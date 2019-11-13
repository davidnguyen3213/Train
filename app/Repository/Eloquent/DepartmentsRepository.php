<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts\DepartmentsInterface as DepartmentsInterface;
use Illuminate\Support\Facades\DB;

class DepartmentsRepository extends BaseRepository implements DepartmentsInterface
{

    protected function model()
    {
        return \App\Departments::class;
    }

    protected function getRules()
    {
        return \App\Departments::rules;
    }

    public function searchWithDepartment(int $id_employer, int $limit = 0, int $offset = 0, array $searchCondition = [], array $orderBy = []){
        $requestModel = \App\Departments::query();
        $requestModel->where('employer_id', $id_employer);
        if ($searchCondition == []) {
            $get_data = $requestModel;
        } else {
            if (isset($searchCondition['department_parent_division'])) {
                $requestModel->where('parent_division', 'like', '%' . $searchCondition['department_parent_division'] . '%');
            }
            if (isset($searchCondition['department_hod'])) {
                $requestModel->where('head_of_department', 'like', '%' . $searchCondition['department_hod'] . '%');
            }
            if (isset($searchCondition['department_hod_mobile'])) {
                $requestModel->where('hod_mobile', 'like', '%' . $searchCondition['department_hod_mobile'] . '%');
            }
            if (isset($searchCondition['department_hod_office_number'])) {
                $requestModel->where('hod_office_number', 'like', '%' . $searchCondition['department_hod_office_number'] . '%');
            }
            if (isset($searchCondition['department_hod_email'])) {
                $requestModel->where('hod_email', 'like', '%' . $searchCondition['department_hod_email'] . '%');
            }
            $get_data = $requestModel;
        }
        $results = $get_data->join("employers", "departments.employer_id", '=', "employers.id")
                            ->select("departments.*", "employers.company as company", "employers.code as code");
        return $results->skip($offset)->take($limit)->orderBy($orderBy[0], $orderBy[1])->get();
    }
    public function countDepartments(int $id_employer,array $searchCondition)
    {
        $requestModel = \App\Departments::query();
        $requestModel->where('employer_id', $id_employer);
        if ($searchCondition == []) {
            $results = $requestModel;
        } else {
            if (isset($searchCondition['department_code'])) {
                $requestModel->where('code', 'like', '%' . $searchCondition['department_code'] . '%');
            }
            if (isset($searchCondition['department_partment_division'])) {
                $requestModel->where('partment_division', 'like', '%' . $searchCondition['department_partment_division'] . '%');
            }
            if (isset($searchCondition['department_hod'])) {
                $requestModel->where('head_of_department', 'like', '%' . $searchCondition['department_hod'] . '%');
            }
            if (isset($searchCondition['department_hod_mobile'])) {
                $requestModel->where('hod_mobile', 'like', '%' . $searchCondition['department_hod_mobile'] . '%');
            }
            if (isset($searchCondition['department_hod_office_number'])) {
                $requestModel->where('hod_office_number', 'like', '%' . $searchCondition['department_hod_office_number'] . '%');
            }
            if (isset($searchCondition['department_hod_email'])) {
                $requestModel->where('hod_email', 'like', '%' . $searchCondition['department_hod_email'] . '%');
            }
            $results = $requestModel;
        }
        return $results->count();
    }
}
