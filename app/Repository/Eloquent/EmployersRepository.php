<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts\EmployersInterface as EmployersInterface;
use Illuminate\Support\Facades\DB;

class EmployersRepository extends BaseRepository implements EmployersInterface
{

    protected function model()
    {
        return \App\Employers::class;
    }

    protected function getRules()
    {
        return \App\Employers::rules;
    }
    /**
     * @param array $searchCondition
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @return array|\Illuminate\Support\Collection
     */
    public function searchWithEmployer(int $limit = 0, int $offset = 0, array $searchCondition = [], array $orderBy = [])
    {
        $requestModel = \App\Employers::query();
        if($searchCondition == []){
            $results = $requestModel;
        }
        else{
            if(isset($searchCondition['employer_code'])){
                $requestModel->where('code', 'like', '%' . $searchCondition['employer_code'] . '%');
            }
            if (isset($searchCondition['employer_company'])) {
                $requestModel->where('company', 'like', '%' . $searchCondition['employer_company'] . '%');
            }
            if (isset($searchCondition['employer_vat'])) {
                $requestModel->where('vat_number', 'like', '%' . $searchCondition['employer_vat'] . '%');
            }
            if (isset($searchCondition['employer_address'])) {
                $requestModel->where('address', 'like', '%' . $searchCondition['employer_address'] . '%');
            }
            if (isset($searchCondition['employer_website'])) {
                $requestModel->where('website', 'like', '%' . $searchCondition['employer_website'] . '%');
            }
            if (isset($searchCondition['employer-company_status']) && $searchCondition['employer-company_status'] != '' ) {
                $requestModel->where('company_status',$searchCondition['employer-company_status']);
            }
            if (isset($searchCondition['employer-payroll_status']) && $searchCondition['employer-payroll_status'] != '') {
                $requestModel->where('payroll_status',$searchCondition['employer-payroll_status']);
            }
            $results = $requestModel;
        }
        return $results->skip($offset)->take($limit)->orderBy($orderBy[0], $orderBy[1])->get();
    }
    public function countEmployer(array $searchCondition = []){
        $requestModel = \App\Employers::query();
        if ($searchCondition == []) {
            $results = $requestModel;
        }
        else{
            if(isset($searchCondition['employer_code'])){
                $requestModel->where('code', 'like', '%' . $searchCondition['employer_code'] . '%');
            }
            if (isset($searchCondition['employer_company'])) {
                $requestModel->where('company', 'like', '%' . $searchCondition['employer_company'] . '%');
            }
            if (isset($searchCondition['employer_vat'])) {
                $requestModel->where('vat_number', 'like', '%' . $searchCondition['employer_vat'] . '%');
            }
            if (isset($searchCondition['employer_address'])) {
                $requestModel->where('address', 'like', '%' . $searchCondition['employer_address'] . '%');
            }
            if (isset($searchCondition['employer_website'])) {
                $requestModel->where('website', 'like', '%' . $searchCondition['employer_website'] . '%');
            }
            if (isset($searchCondition['employer-company_status']) && $searchCondition['employer-company_status'] != '') {
                $requestModel->where('company_status', $searchCondition['employer-company_status']);
            }
            if (isset($searchCondition['employer-payroll_status']) && $searchCondition['employer-payroll_status'] != '') {
                $requestModel->where('payroll_status', $searchCondition['employer-payroll_status']);
            }
            $results = $requestModel;
        }
        return $requestModel->count();
    }

}
