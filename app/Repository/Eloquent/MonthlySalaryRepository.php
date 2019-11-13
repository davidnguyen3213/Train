<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts\MonthlySalaryInterface as MonthlySalaryInterface;
use Illuminate\Support\Facades\DB;
class MonthlySalaryRepository extends BaseRepository implements MonthlySalaryInterface
{

    protected function model()
    {
        return \App\MonthlySalary::class;
    }

    protected function getRules()
    {
        return \App\MonthlySalary::rules;
    }

    /**
     * @param array $searchCondition
     * @return array
     */
    public function search(array $searchCondition = [])
    {
        $requestMonthlySalaryModel = DB::table('monthly_salary as ms')
            ->join("employers as er", "ms.employer_id", '=', "er.id")
            ->join("employees as e", "ms.employee_id", '=', "e.id");

        $yearMonth = isset($searchCondition["year_month"]) ? $searchCondition["year_month"] : date('Ym');
        $requestMonthlySalaryModel = $requestMonthlySalaryModel->where('ms.year_month','<=', $yearMonth);
        $requestMonthlySalaryModel = $requestMonthlySalaryModel->where('ms.employee_id','=', $searchCondition['employee_id']);
        $requestMonthlySalaryModel = $requestMonthlySalaryModel->where('ms.employer_id','=', $searchCondition['employer_id']);
        $requestMonthlySalaryModel->orderBy('year_month', 'desc');
        $results = $requestMonthlySalaryModel
            ->select(['ms.*', 'e.weway_customer_number as weway_customer_number', 'e.surname as surname', 'e.middle_name as middle_name',
                'e.name as name', 'e.employee_number as employee_number'])
            ->get()->first();

        if ($results) {
            return $results;
        } else {
            return array();
        }
    }
    public function insertMonthSalary(){
        $model = \App\MonthlySalary::query();
        $last_month = getPreviousMonth();
        $records = $model->where('year_month', $last_month)->get();
        $data_insert = [];
        if($records != null){
            foreach($records as $record){
                array_push( $data_insert , [
                    "employee_id" => $record['employee_id'],
                    "employer_id" => $record['employer_id'],
                    "employee_number" => $record['employee_number'],
                    "department_id" => $record['department_id'],
                    "weway_customer_number" => $record['weway_customer_number'],
                    "contractual_salary" => $record['contractual_salary'],
                    "net_salary" => $record['net_salary'],
                    "standard_working_days" => $record['standard_working_days'],
                    "working_day_adjustment" => $record['working_day_adjustment'],
                    "actual_working_days" => $record['actual_working_days'],
                    "payroll_payment_date" =>  $record['payroll_payment_date'],
                    "advance_all_salary_date" =>  $record['advance_all_salary_date'],
                    "advance_date_adjustment" =>  $record['advance_date_adjustment'],
                    "advance_percentage" =>  $record['advance_percentage'],
                    "fee_tariff" =>  $record['fee_tariff'],
                    "year_month" => date("Ym"),
                    'created_at' => date("Y-m-d h:i:s"),
                    'updated_at' => date("Y-m-d h:i:s")
                ]);
            }
            return $model->insert($data_insert);
        }
    }
}
