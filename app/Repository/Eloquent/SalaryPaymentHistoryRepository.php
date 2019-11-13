<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts\SalaryPaymentHistoryInterface as SalaryPaymentHistoryInterface;
use Illuminate\Support\Facades\DB;

class SalaryPaymentHistoryRepository extends BaseRepository implements SalaryPaymentHistoryInterface
{

    protected function model()
    {
        return \App\SalaryPaymentHistory::class;
    }

    protected function getRules()
    {
        return \App\SalaryPaymentHistory::rules;
    }

    /**
     * @param $employee_id
     * @param $year_month
     * @param $field
     * @return mixed
     */
    public function sumValues($employee_id, $year_month, $field) {
        return $this->model->where('employee_id', '=', $employee_id)
            ->where('year_month', '=', $year_month)
            ->sum($field);
    }

    /**
     * @param $employee_id
     * @param $year_month
     * @param $date
     * @param $field
     * @return mixed
     */
    public function sumValuesByDate($employee_id, $year_month, $date ,$field) {
        return $this->model->where('employee_id', '=', $employee_id)
            ->where('date', '=', $date)
            ->where('year_month', '=', $year_month)
            ->sum($field);
    }

    public function getDescriptionByDate($employee_id, $year_month, $date) {
        return $this->model->select(DB::raw('group_concat(description SEPARATOR ", \n") as description'))
            ->where('employee_id', '=', $employee_id)
            ->where('date', '=', $date)
            ->where('year_month', '=', $year_month)
            ->groupBy('employee_id','date')
            ->get()->first();
    }


}
