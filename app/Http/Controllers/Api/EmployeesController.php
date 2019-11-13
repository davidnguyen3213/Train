<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\ActiveAccountRequest;
use App\Http\Requests\CheckActivatedEmailRequest;
use App\Http\Requests\CheckExistEmailRequest;
use App\Http\Requests\CheckValidAccountRequest;
use App\Http\Requests\PayrollConfirmRequest;
use App\Http\Requests\RegisterAccountRequest;
use App\Http\Requests\RequestsForAdvancesRequest;
use App\Http\Requests\VerificationCodeRequest;
use App\Http\Requests\UpdatePinCodeRequest;
use App\Repository\Eloquent\EmployeesRepository;
use App\Repository\Eloquent\EmployersRepository;
use App\Repository\Eloquent\MonthlySalaryRepository;
use App\Repository\Eloquent\SalaryPaymentHistoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendActivationCodeMail;
use App\Mail\SendRequestAdvanceMail;
use App\Mail\SendVerificationCodeMail;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\DB;


class EmployeesController extends BaseController
{

    protected $employeesRepository;
    protected $employersRepository;
    protected $monthlySalaryRepository;
    protected $salaryPaymentHistoryRepository;
    protected $smsOtp;

    public function __construct(
        EmployeesRepository $employeesRepository,
        EmployersRepository $employersRepository,
        MonthlySalaryRepository $monthlySalaryRepository,
        SalaryPaymentHistoryRepository $salaryPaymentHistoryRepository
    )
    {
        $this->employeesRepository = $employeesRepository;
        $this->employersRepository = $employersRepository;
        $this->monthlySalaryRepository = $monthlySalaryRepository;
        $this->salaryPaymentHistoryRepository = $salaryPaymentHistoryRepository;
    }

    /**
     * check exist email
     * @param CheckExistEmailRequest $request
     */
    public function checkExistEmail(CheckExistEmailRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'work_email' => $request['email']
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Email does not exist'), 1);
            }

            if ($employee['is_active'] == 1) {
                return $this->sendResponse([], __('Account was activated'), 1);
            }

            $activation_code = generateRandomString(8);

            $this->employeesRepository->update([
                'activation_code' => $activation_code
            ], $employee['id']);

            Mail::to($request['email'])->send(New SendActivationCodeMail($activation_code));
            return $this->sendResponse([], __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param CheckActivatedEmailRequest $request
     * @return \Illuminate\Http\Response
     */
    public function checkActivatedEmail(CheckActivatedEmailRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'work_email' => $request['email'],
                'is_active' => 1
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Email does not exist or nonactive'), 1);
            }

            $verification_code = generateRandomString(8);

            $this->employeesRepository->update([
                'activation_code' => $verification_code
            ], $employee['id']);

            Mail::to($request['email'])->send(New SendVerificationCodeMail($verification_code));
            return $this->sendResponse([], __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param VerificationCodeRequest $request
     */
    public function checkVerificationCode(VerificationCodeRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'work_email' => $request['email'],
                'activation_code' => $request['verification_code'],
                'is_active' => 1
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Verification Failed'), 1);
            }

            return $this->sendResponse([], __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param UpdatePinCodeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePinCode(UpdatePinCodeRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'work_email' => $request['email'],
                'is_active' => 1,
                'activation_code' => $request['verification_code']
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Account is not valid'), 1);
            }

            $this->employeesRepository->update([
                'pincode' => Hash::make($request['pincode']),
                'activation_code' => '',
            ], $employee['id']);

            return $this->sendResponse([], __('Update pin code successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param CheckValidAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function checkValidAccount(CheckValidAccountRequest $request) {
        try {
            $username = $request['username'];

            $query = \App\Employees::where(function ($query1) use ($username) {
                    $query1->where('work_email', '=', $username)
                        ->orWhere('mobile', '=', $username);
                });

            $employees = $query->get()->first();
            if (empty($employees)) {
                return $this->sendResponse([], __('Email or mobile does not exist.'), 1, true);
            }
            return $this->sendResponse(['pincode_hint' => $employees->pincode_hint], __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param ActiveAccountRequest $request
     */
    public function activeAccount(ActiveAccountRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'work_email' => $request['email'],
                'activation_code' => $request['active_code'],
                'is_active' => 0
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Activation failed. Email or activation code is not correct.'), 1);
            }

            return $this->sendResponse([], __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * Register api
     *
     * @param  RegisterAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterAccountRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'work_email' => $request['email'],
                'is_active' => 0,
                'activation_code' => $request['active_code']
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Account is not valid'), 1);
            }

            $this->employeesRepository->update([
                'mobile' => $request['mobile'],
                'pincode' => Hash::make($request['pincode']),
                'pincode_hint' => $request['pincode_hint'],
                'is_active' => 1,
                'activation_code' => ''
            ], $employee['id']);

            return $this->sendResponse([], __('Register successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        try {
            $username = request('username');

            $query = \App\Employees::where('is_active', '=', 1)
                ->where('status', '=', 1)
                ->where(function ($query1) use ($username) {
                    $query1->where('work_email', '=', $username)
                        ->orWhere('mobile', '=', $username);
                });

            $employees = $query->get();

            $result = findUserORCompanyLogin($employees, request('pincode'));

            if (empty($result)) {
                return $this->sendResponse([], __('Login failed'), 1);
            }

            $success = $result;
            $success['token'] = 'Bearer ' . $result->createToken('Laravel Password Grant Client')->accessToken;

            return $this->sendResponse($employees, __('User login successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param RequestsForAdvancesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function requestsForAdvances(RequestsForAdvancesRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'id' => $request['employee_id'],
                'status' => 1,
                'is_active' => 1
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Account is not valid'), 1);
            }

            $data = [];

            $monthlySalary = $this->monthlySalaryRepository->firstWhereAll(
                ['employee_id' => $request['employee_id']], 'year_month', 'desc'
            );

            if (empty($monthlySalary)) {
                return $this->sendResponse([], __('Account is not valid'), 1);
            }

            $currentMonth = date('Ym');
            $today = date('d');
            $data['date'] = getCurrentTimeInMilliseconds();
            if ($monthlySalary['year_month'] < $currentMonth) {
                $data['salary'] = $monthlySalary['net_salary'];

                if ($today < $monthlySalary['payroll_payment_date']) {
                    $data['year_month'] = $monthlySalary['year_month'];
                    $data['cumulative_day'] = $monthlySalary['standard_working_days'];
                } else {
                    $data['year_month'] = $currentMonth;
                    $data['cumulative_day'] = $this->_calculatorCumulativeDay($today);

                    if ($today >= $monthlySalary['advance_all_salary_date']) {
                        $data['cumulative_day'] = $monthlySalary['standard_working_days'];
                    }
                }
            } else {
                if ($today < $monthlySalary['payroll_payment_date']) {
                    $monthlySalary = $this->monthlySalaryRepository->firstWhereAll([
                        ['employee_id', '=', $request['employee_id']],
                        ['year_month', '<=', getPreviousMonth()]
                    ], 'created_at', 'desc');
                    $data['salary'] = $monthlySalary['net_salary'];
                    $data['year_month'] = getPreviousMonth();
                    $data['cumulative_day'] = $monthlySalary['standard_working_days'];
                } else {
                    $data['salary'] = $monthlySalary['net_salary'];
                    $data['year_month'] = $currentMonth;
                    $data['cumulative_day'] = $this->_calculatorCumulativeDay($today);

                    if ($today >= $monthlySalary['advance_all_salary_date']) {
                        $data['cumulative_day'] = $monthlySalary['standard_working_days'];
                    }
                }
            }
            //TODO: https://gitlab.2nf.com.vn/2019/we-way-server/issues/12
            $data['daily_drawable_amount'] = (int)$monthlySalary['net_salary'] / (int)$monthlySalary['standard_working_days'] * (int)$monthlySalary['actual_working_days'] / (int)$monthlySalary['standard_working_days'];
            $data['drawable_amount'] =  $data['daily_drawable_amount'] * (int)$data['cumulative_day'];
            $data['total_advance'] = $this->salaryPaymentHistoryRepository->sumValues($request['employee_id'], $data['year_month'], 'advance');
            $data['total_payment'] = $this->salaryPaymentHistoryRepository->sumValues($request['employee_id'], $data['year_month'], 'payment');
            $data['remaining_drawable'] = $data['drawable_amount'] - $data['total_advance'] + $data['total_payment'];
            $data['fee_tariff'] = $monthlySalary['fee_tariff'];

            return $this->sendResponse($data, __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }
    }

    /**
     * @param $day
     * @return int
     */
    private function _calculatorCumulativeDay($day)
    {
        $begin = new DateTime(date('Y-m-01'));
        $end = new DateTime(date('Y-m-d'));

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end->modify( '+1 day' ));
        $i = 0;
        foreach ($period as $dt) {
            if(date('D', strtotime($dt->format("l Y-m-d H:i:s"))) == 'Sat' || date('D', strtotime($dt->format("l Y-m-d H:i:s"))) == 'Sun') {
                continue;
            }
            $i++;
        }
        return $i;
    }

    /**
     * @param PayrollConfirmRequest $request
     * @return \Illuminate\Http\Response
     */
    public function payrollConfirm(PayrollConfirmRequest $request)
    {
        try {
            $employee = $this->employeesRepository->firstWhere([
                'id' => $request['employee_id'],
                'status' => 1,
                'is_active' => 1
            ]);

            if (empty($employee)) {
                return $this->sendResponse([], __('Account is not valid'), 1);
            }

            $data = [];

            $monthlySalary = $this->monthlySalaryRepository->firstWhereAll(
                ['employee_id' => $request['employee_id']], 'year_month', 'desc'
            );

            if (empty($monthlySalary)) {
                return $this->sendResponse([], __('Account is not valid'), 1);
            }

            $monthlySalary = $this->monthlySalaryRepository->firstWhereAll([
                ['employee_id', '=', $request['employee_id']],
                ['year_month', '<=', $request['year_month']]
            ], 'year_month', 'desc');

            if (empty($monthlySalary)) {
                return $this->sendResponse([], __('Request Advance Failed'), 1);
            }

            $currentMonth = date('Ym');
            $today = date('d');
            $data['date'] = getCurrentTimeInMilliseconds();
            if ($request['year_month'] < $currentMonth) {
                if ($this->_checkValidTimeToApplySalary($today, $monthlySalary['payroll_payment_date'], $monthlySalary['advance_date_adjustment'])) {
                    $data['salary'] = $monthlySalary['net_salary'];
                    $data['year_month'] = $monthlySalary['year_month'];
                    $data['cumulative_day'] = $monthlySalary['standard_working_days'];
                } else {
                    $startDate = getStartDateForRequestAdvance($request['year_month'], $monthlySalary['payroll_payment_date'], \Config::get('constants.RANGE_START_REQUEST_ADVANCE'), 'd/m/Y');
                    $endDate = getEndDateForRequestAdvance($this->_getNextMonthOfTheMonth($request['year_month']), $monthlySalary['payroll_payment_date'],  $monthlySalary['advance_date_adjustment'], 'd/m/Y');
                    $currentMonth = $this->_getCurrentMonthForRequestAdvance($request['year_month'], 'm/Y');
                    return $this->sendResponse([], __('Thời gian ứng lương tháng ' . $currentMonth . ' bắt đầu từ ' . $startDate . ' đến ' . $endDate), 2);
                }
            } else {
                if (($this->_checkValidTimeToApplySalary($monthlySalary['payroll_payment_date'], $today, \Config::get('constants.RANGE_START_REQUEST_ADVANCE'))) &&
                    ($this->_checkValidTimeToApplySalaryAdvance(date('Y-m-d'), getNextMonth('Y-m') . '-' . $monthlySalary['payroll_payment_date'], $monthlySalary['advance_date_adjustment']))
                ) {

                    $data['salary'] = $monthlySalary['net_salary'];
                    $data['year_month'] = $currentMonth;
                    $data['cumulative_day'] = $this->_calculatorCumulativeDay($today);

                    if ($today >= $monthlySalary['advance_all_salary_date']) {
                        $data['cumulative_day'] = $monthlySalary['standard_working_days'];
                    }
                } else {
                    $startDate = getStartDateForRequestAdvance($request['year_month'], $monthlySalary['payroll_payment_date'], \Config::get('constants.RANGE_START_REQUEST_ADVANCE'), 'd/m/Y');
                    $endDate = getEndDateForRequestAdvance($this->_getNextMonthOfTheMonth($request['year_month']), $monthlySalary['payroll_payment_date'],  $monthlySalary['advance_date_adjustment'], 'd/m/Y');
                    $currentMonth = $this->_getCurrentMonthForRequestAdvance($request['year_month'], 'm/Y');
                    return $this->sendResponse([], __('Thời gian ứng lương tháng ' . $currentMonth . ' bắt đầu từ ' . $startDate . ' đến ' . $endDate), 2);
                }
            }
            //TODO: https://gitlab.2nf.com.vn/2019/we-way-server/issues/12
            $data['daily_drawable_amount'] = (int)$monthlySalary['net_salary'] / (int)$monthlySalary['standard_working_days'] * (int)$monthlySalary['actual_working_days'] / (int)$monthlySalary['standard_working_days'];
            $data['drawable_amount'] =  $data['daily_drawable_amount'] * (int)$data['cumulative_day'];
            $data['total_advance'] = $this->salaryPaymentHistoryRepository->sumValues($request['employee_id'], $data['year_month'], 'advance');
            $data['total_payment'] = $this->salaryPaymentHistoryRepository->sumValues($request['employee_id'], $data['year_month'], 'payment');
            $data['remaining_drawable'] = $data['drawable_amount'] - $data['total_advance'] + $data['total_payment'];

            if ($request['advance'] > $data['remaining_drawable']) {
                return $this->sendResponse([], __('Advance is greater than remaining drawable'), 1);
            }

            $this->salaryPaymentHistoryRepository->create([
                'employee_id' => $request['employee_id'],
                'date' => date('Y-m-d'),
                'year_month' => $request['year_month'],
                'advance' => $request['advance'],
            ]);

            $mailData['weway_customer_number'] = $monthlySalary['weway_customer_number'];
            $mailData['employee_name'] = $employee['surname'] . ' ' . $employee['middle_name'] . ' ' . $employee['name'];
            $mailData['employer_name'] = $this->employersRepository->firstWhere(['id' => $monthlySalary['employer_id']], ['company'])->company;
            $mailData['advance'] = number_format($request['advance']);
            $fee = $request['advance'] * $monthlySalary['fee_tariff'] / 100;
            $mailData['fee_tariff'] = number_format($fee);
            $mailData['actual_advance'] = number_format($request['advance'] - $fee);
            $mailData['bank'] = $employee['bank'];
            $mailData['bank_account'] = $employee['bank_account'];
            $mailData['year_month'] = date('m-Y', strtotime($data['year_month']));

            Mail::to('account@weway.com.vn')->send(New SendRequestAdvanceMail($mailData));

            return $this->sendResponse([], __('Successfully.'));
        } catch (\Exception $ex) {
            return $this->sendError(__($ex->getMessage()));
        }

    }

    /**
     * @param $yearMonth
     * @param string $format
     * @return false|string
     */
    private  function _getCurrentMonthForRequestAdvance($yearMonth, $format = "m/Y") {
        $date = $yearMonth . "01";
        return date($format, strtotime($date));
    }

    /**
     * @param $yearMonth
     * @param string $format
     * @return string
     */
    private function _getNextMonthOfTheMonth($yearMonth,$format = "Ym") {
        $date = $yearMonth . "01";
        $date = new DateTime(date($date));
        $date->modify( '+1 months');
        return $date->format($format);
    }

    /**
     * @param $begin
     * @param $end
     * @param $range
     */
    private function _checkValidTimeToApplySalary($begin, $end, $range)
    {
        $begin = new DateTime(date('Y-m-' . $begin));
        $end = new DateTime(date('Y-m-' . $end));
        if ($begin <= $end->modify( '-' . $range . ' days' )) {
            return true;
        }

        return false;
    }

    /**
     * @param $begin
     * @param $end
     * @param $range
     */
    private function _checkValidTimeToApplySalaryAdvance($begin, $end, $range)
    {
        $begin = new DateTime(date($begin));
        $end = new DateTime(date($end));
        if ($begin <= $end->modify( '-' . $range . ' days' )) {
            return true;
        }

        return false;
    }
}