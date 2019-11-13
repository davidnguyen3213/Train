<?php
namespace App\Http\Controllers\Admin;

use App\Repository\Eloquent\DepartmentsRepository;
use App\Repository\Eloquent\EmployeesRepository;
use App\Repository\Eloquent\EmployersRepository;
use App\Repository\Eloquent\MonthlySalaryRepository;
use App\Repository\Eloquent\SalaryPaymentHistoryRepository;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeFormRequest;
use App\Http\Requests\EmployeePayrollFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use App\Helpers\TransFormatApi;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\If_;
use Validator;
use DateTime;
use DateInterval;
use DatePeriod;

/**
 * @property EmployeesRepository EmployeesRepository
 */
class EmployeePayrollController extends AdminBaseController
{
    protected $departmentsRepository;
    protected $employeesRepository;
    protected $employersRepository;
    protected $monthlySalaryRepository;
    protected $salaryPaymentHistoryRepository;


    /**
     * EmployeeController constructor.
     * @param DepartmentsRepository $departmentsRepository
     * @param EmployeesRepository $employeesRepository
     * @param EmployersRepository $employersRepository
     * @param MonthlySalaryRepository $monthlySalaryRepository
     */
    public function __construct(
        DepartmentsRepository $departmentsRepository,
        EmployeesRepository $employeesRepository,
        EmployersRepository $employersRepository,
        MonthlySalaryRepository $monthlySalaryRepository,
        SalaryPaymentHistoryRepository $salaryPaymentHistoryRepository
    )
    {
        parent::__construct();

        $this->departmentsRepository = $departmentsRepository;
        $this->employeesRepository = $employeesRepository;
        $this->employersRepository = $employersRepository;
        $this->monthlySalaryRepository = $monthlySalaryRepository;
        $this->salaryPaymentHistoryRepository = $salaryPaymentHistoryRepository;
    }

    /**
     * @param Request $request
     * @param int $employee_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $employee_id = 0)
    {
        $searchData = $request->only('year_month');
        if (!is_numeric($employee_id) || $employee_id <= 0) {
            return redirect()->route('employee.index')->withError('Employee not found.');
        }

        $employee = $this->employeesRepository->firstWhere([
            'id' => $employee_id
        ]);

        if (empty($employee)) {
            return redirect()->route('employee.index')->withError('Employee not found.');
        }

        $searchData['employee_id'] = $employee_id;
        $searchData['employer_id'] = $employee->employer_id;
        $yearMonth = isset($searchData['year_month']) ? $searchData['year_month'] : date('m-Y');
        $yearMonth = date('01-' . $yearMonth);
        $searchData['year_month'] = date('Ym', strtotime($yearMonth));
        $employeePayroll = $this->monthlySalaryRepository->search($searchData);

        $payroll = [];
        if (!empty($employeePayroll)) {
            $employeePayroll->year_month = $searchData['year_month'];
            $yearMonth = date('Y-m-d', strtotime($yearMonth));
            $payroll = $this->_calculatorPayroll($employeePayroll, $yearMonth);
        }


        $enableToUpdate = false;
        if ($searchData['year_month'] == date('Ym') && date('d') <= \Config::get('constants.ENABLE_UPDATED_DATE')) {
            $enableToUpdate = true;
        }

        $this->viewData['employeeData'] = [
            'employee_id' => $employee_id,
            'enableToUpdate' => $enableToUpdate,
            'employee' => $employee,
            'payroll' => $payroll,
            'employee_payroll' => $employeePayroll,
            'year_month' => $searchData['year_month'],
            'searchValue' => [
                'year_month' => isset($request['year_month']) ? $request['year_month'] : date('m-Y')
            ],
        ];

        return view('admin.monthly_salary.index', $this->viewData);
    }

    /**
     * @param $employeeID
     * @param $yearMonth
     * @param $payrollPaymentDate
     * @return array
     */
    private function _calculatorPayroll($employeePayroll, $yearMonth)
    {
        $begin = new DateTime(date($yearMonth));
        $end = $this->_getPayrollPaymentTime($yearMonth, $employeePayroll->payroll_payment_date);
        $end = new DateTime(date($end));

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end->modify( '+1 day' ));
        $i = 0;
        $j = 0;
        $data = [];
        //TODO: https://gitlab.2nf.com.vn/2019/we-way-server/issues/12
        $dailyDrawableAmount = (($employeePayroll->net_salary / $employeePayroll->standard_working_days) * $employeePayroll->actual_working_days) / $employeePayroll->standard_working_days;
        foreach ($period as $dt) {

            $data[$i]['date'] = $dt->format('Y-m-d');
            $data[$i]['day'] = date('D', strtotime($dt->format("l Y-m-d H:i:s")));
            $data[$i]['working_day'] = 1;

            if(date('D', strtotime($dt->format("l Y-m-d H:i:s"))) == 'Sat' || date('D', strtotime($dt->format("l Y-m-d H:i:s"))) == 'Sun') {
                $data[$i]['working_day'] = 0;
            } else if($j >= $employeePayroll->standard_working_days || $dt->format('d') >= $employeePayroll->advance_all_salary_date) {
                $j = $employeePayroll->standard_working_days;
            } else {
                $j++;
            }

            $data[$i]['cumulative_day'] = $j;
            $data[$i]['drawable_amount'] = $dailyDrawableAmount * $j;
            $data[$i]['advanced'] = -$this->salaryPaymentHistoryRepository->sumValuesByDate($employeePayroll->employee_id, $employeePayroll->year_month, $dt->format('Y-m-d'), 'advance');
            $data[$i]['payment'] = $this->salaryPaymentHistoryRepository->sumValuesByDate($employeePayroll->employee_id, $employeePayroll->year_month, $dt->format('Y-m-d'), 'payment');
            $description =  $this->salaryPaymentHistoryRepository->getDescriptionByDate($employeePayroll->employee_id, $employeePayroll->year_month, $dt->format('Y-m-d'));
            $data[$i]['description'] = empty($description) ? '' : $description->description;
            $totalAdvance[] = $data[$i]['advanced'];
            $data[$i]['total_advance'] = array_sum($totalAdvance);
            $totalPayment[] = $data[$i]['payment'];
            $data[$i]['total_payment'] = array_sum($totalPayment);
            $data[$i]['net_amount'] = $data[$i]['total_advance'] + $data[$i]['total_payment'];
            $data[$i]['remaining_drawable'] = $data[$i]['drawable_amount'] + $data[$i]['net_amount'];
            $data[$i]['enable_edit'] = $i > ($employeePayroll->payroll_payment_date) ? true : false;

            $i++;
        }
        return $data;
    }

    /**
     * @param $yearMonth
     * @param $payrollPaymentDate
     * @return string
     */
    private function _getPayrollPaymentTime($yearMonth, $payrollPaymentDate) {
        $date = $yearMonth;
        $date = new DateTime(date($date));
        $date->modify( '+1 months');
        $date->modify( '+' . ($payrollPaymentDate -1) .' days');
        return $date->format('Y-m-d');
    }

    /**
     * @param EmployeePayrollFormRequest $request
     * @param int $employee_id
     * @return mixed
     */
    public function store(EmployeePayrollFormRequest $request, $employee_id = 0)
    {
        $data = $request->all();


        if (!is_numeric($employee_id) || $employee_id <= 0) {
            return redirect()->route('employee.index')->withError('Employee not found.');
        }

        $employee = $this->employeesRepository->firstWhere([
            'id' => $employee_id
        ]);

        if (empty($employee)) {
            return redirect()->route('employee.index')->withError('Employee not found.');
        }

        if ($data['year_month'] != date('m-Y') || date('d') > \Config::get('constants.ENABLE_UPDATED_DATE')) {
            return redirect()->route('employee-payroll.index', $employee_id)->withError('You can only update before the 5th of the month.');
        }

        $yearMonth = isset($data['year_month']) ? $data['year_month'] : date('m-Y');
        $yearMonth = date('01-' . $yearMonth);
        $data['year_month'] = date('Ym', strtotime($yearMonth));

        $condition = [
            ['id', "=", $employee->employer_id]
        ];

        $employer = $this->employersRepository->firstWhere($condition);

        if (empty($employer)) {
            return redirect()->route('employees.index')->withError('Save error. Employer not found.');
        }

        $monthlySalary = $this->monthlySalaryRepository->firstWhereAll([
            ['employee_id', '=', $employee->id],
            ['employer_id', '=', $employee->employer_id],
            ['year_month', '<=', $data['year_month']]
        ], 'year_month', 'desc');

        if (empty($monthlySalary)) {
            return redirect()->route('employees.index')->withError('Save error.');
        }

        if ($monthlySalary->year_month == $data['year_month']) {
            $this->monthlySalaryRepository->update([
                'contractual_salary' => $data['contractual_salary'],
                'net_salary' => $data['net_salary'],
                'standard_working_days' => $data['standard_working_days'],
                'working_day_adjustment' => $data['working_day_adjustment'],
                'actual_working_days' => $data['actual_working_days'],
            ], $monthlySalary->id);
        } else {
            $this->monthlySalaryRepository->create([
                'employee_id' => $employee->id,
                'employer_id' => $employer->id,
                'year_month' => $data['year_month'],
                'contractual_salary' => $data['contractual_salary'],
                'net_salary' => $data['net_salary'],
                'standard_working_days' => $data['standard_working_days'],
                'working_day_adjustment' => $data['working_day_adjustment'],
                'actual_working_days' => $data['actual_working_days'],
                'payroll_payment_date' => $employer->payroll_payment_date,
                'advance_all_salary_date' => $employer->advance_all_salary_date,
                'advance_date_adjustment' => $employer->advance_date_adjustment,
                'fee_tariff' => $employer->fee_tariff,
            ]);
        }

        return redirect()->route('employee-payroll.index', $employee_id)->withSuccess('Update successfully.');
    }

    /**
     * @param Request $request
     * @param int $employee_id
     * @return mixed
     */
    public function update(Request $request, $employee_id = 0) {
        $data = $request->all();
        if (!is_numeric($employee_id) || $employee_id <= 0) {
            return redirect()->route('employee.index')->withError('Employee not found.');
        }

        $employee = $this->employeesRepository->firstWhere([
            'id' => $employee_id
        ]);

        if (empty($employee)) {
            return redirect()->route('employee.index')->withError('Employee not found.');
        }

        $salaryHistory = $this->salaryPaymentHistoryRepository->firstWhere([
            ['employee_id', '=', $employee_id],
            ['year_month', '=', $data['year_month']],
            ['date', '=', $data['date']],
            ['payment', '>', 0],
        ]);

        if (empty($salaryHistory)) {
            $this->salaryPaymentHistoryRepository->create([
                'employee_id' =>  $employee_id,
                'year_month' =>  $data['year_month'],
                'date' =>  $data['date'],
                'payment' => $data['payment'],
                'description' => $data['description']
            ]);
        } else {
            $this->salaryPaymentHistoryRepository->update([
                'payment' => $data['payment'],
                'description' => $data['description']
            ], $salaryHistory->id);
        }

        return $this->viewData['employeesData'] = $data;
    }

    /**
     * @param Request $request
     * @param $employer_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDepartmentsByEmployerId(Request $request, $employer_id) {
        if ( !$employer_id || !is_numeric($employer_id) ) {
            return redirect()->route('error');
        }

        $departments = $this->departmentsRepository->findWhereAll([
            'employer_id' => $employer_id
        ], '', '', ['id', 'department']);

        $this->viewData['employeesData'] = [
            'departments' => $departments,
        ];

        return $this->viewData;
    }
}
