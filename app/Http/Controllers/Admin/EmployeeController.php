<?php
namespace App\Http\Controllers\Admin;

use App\Repository\Eloquent\DepartmentsRepository;
use App\Repository\Eloquent\EmployeesRepository;
use App\Repository\Eloquent\EmployersRepository;
use App\Repository\Eloquent\MonthlySalaryRepository;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use App\Helpers\PushNotification;
use App\Helpers\TransFormatApi;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * @property EmployeesRepository EmployeesRepository
 */
class EmployeeController extends AdminBaseController
{
    protected $departmentsRepository;
    protected $employeesRepository;
    protected $employersRepository;
    protected $monthlySalaryRepository;


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
        MonthlySalaryRepository $monthlySalaryRepository
    )
    {
        parent::__construct();

        $this->departmentsRepository = $departmentsRepository;
        $this->employeesRepository = $employeesRepository;
        $this->employersRepository = $employersRepository;
        $this->monthlySalaryRepository = $monthlySalaryRepository;
    }

    public function index(Request $request)
    {
        $searchData = $request->all();
        $currentPage =  isset($searchData['page']) ? $searchData['page'] : 1;

        if ( !$currentPage || !is_numeric($currentPage) || $currentPage < 1 ) {
            $currentPage = 1;
        }

        // Record counts in a page.
        $numberPerPage = config('constants.NUMBER_PERPAGE');

        if (!is_array($searchData)) {
            $searchData = [];
        }

        // Total record of .
        $total = $this->employeesRepository->countSearchWithEmployees($searchData);

        // Total pagination
        $totalPage = ceil($total/$numberPerPage);

        if ( $currentPage > $totalPage ) {
            $currentPage = $totalPage;
        }

        $offset = ($currentPage-1)*$numberPerPage;
        $orderBy = [];
        $order = isset($searchData['order']) ? $searchData['order'] : 'created_at';
        $sort = isset($searchData['sort']) ? $searchData['sort'] : 'desc';
        $orderBy = [$order,$sort];

        $employees = $this->employeesRepository->searchWithEmployees($searchData,$numberPerPage,$offset,$orderBy);

        $employers = $this->employersRepository->findWhereAll([
            'company_status' => 1,
            'payroll_status' => 1
        ], '', '', ['id', 'company']);

        $this->viewData['employeesData'] = [
            'employees' => $employees,
            'employers' => $employers,
            'searchValue' => [
                'employee_status' => isset($searchData['employee_status']) ? $searchData['employee_status'] : null,
                'employee_name' => isset($searchData['employee_name']) ? $searchData['employee_name'] : null,
                'employee_phone' => isset($searchData['employee_phone']) ? $searchData['employee_phone'] : null,
                'employee_mail' => isset($searchData['employee_mail']) ? $searchData['employee_mail'] : null,
                'employee_employer' => isset($searchData['employee_employer']) ? $searchData['employee_employer'] : null,
                'sort' => $sort,
                'order' => $order
            ],
            "page" => [
                "total" => $total,
                "totalPage" => $totalPage,
                "currentPage" => $currentPage,
            ]
        ];

        return view('admin.employee.index', $this->viewData);
    }



    /**
     * Edit user.
     *
     * @param Request $request
     * @param $employee_id
     */
    public function showEditForm(Request $request, $employee_id)
    {
        if ( !$employee_id || !is_numeric($employee_id) ) {
            return redirect()->route('error');
        }

        $condition = [
            ['id', "=", $employee_id]
        ];

        $employee = $this->employeesRepository->firstWhere($condition);
        if ( !$employee ) {
            return redirect()->route('employee.index')->withError('Add error. Employee not found.');
        }

        $departments = $this->departmentsRepository->findWhereAll([
            'employer_id' => $employee->employer_id
        ], '', '', ['id', 'department']);

        $this->viewData['employeesData'] = [
            'employee' => $employee,
            'departments' => $departments
        ];

        return $this->viewData;
    }

    /**
     * @param EmployeeFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EmployeeFormRequest $request)
    {
        $data = $request->only(
            "employee_store_weway_customer_number",
            "employee_store_surname",
            "employee_store_middle_name",
            "employee_store_name",
            "employee_store_mobile",
            "employee_store_work_number",
            "employee_store_home_number",
            "employee_store_date_of_birth",
            "employee_store_work_email",
            "employee_store_personal_email",
            "employee_store_joining_date",
            "employee_store_leaving_date",
            "employee_store_id",
            "employee_store_bank_account",
            "employee_store_bank",
            "employee_store_employee_number",
            "employee_store_id_number",
            "employee_store_address",
            "employee_store_personal_tax_code",
            "employee_store_facebook",
            "employee_store_linkedin",
            "employee_store_education",
            "employee_store_status",
            "employee_store_gender",
            "employee_store_marital_status",
            "employee_store_children",
            "employee_store_is_active",
            "employee_store_employer",
            "employee_store_department"
        );

        $currentEmployeeId = $data['employee_store_id'];
        //add new user
        if ( !$currentEmployeeId ) {
            unset($data["employee_store_id"]);

            //add data to db
            $createData = [
                'weway_customer_number' => $data['employee_store_weway_customer_number'],
                'surname' => $data['employee_store_surname'],
                'middle_name' => $data['employee_store_middle_name'],
                'name' => $data['employee_store_name'],
                'mobile' => $data['employee_store_mobile'],
                'work_number' => $data['employee_store_work_number'],
                'home_number' => $data['employee_store_home_number'],
                'date_of_birth' => $data['employee_store_date_of_birth'],
                'work_email' => $data['employee_store_work_email'],
                'personal_email' => $data['employee_store_personal_email'],
                'joining_date' => $data['employee_store_joining_date'],
                'leaving_date' => $data['employee_store_leaving_date'],
                'bank_account' => $data['employee_store_bank_account'],
                'bank' => $data['employee_store_bank'],
                'employee_number' => $data['employee_store_employee_number'],
                'id_number' => $data['employee_store_id_number'],
                'address' => $data['employee_store_address'],
                'personal_tax_code' => $data['employee_store_personal_tax_code'],
                'facebook' => $data['employee_store_facebook'],
                'linkedin' => $data['employee_store_linkedin'],
                'education' => $data['employee_store_education'],
                'status' => $data['employee_store_status'],
                'gender' => $data['employee_store_gender'],
                'marital_status' => $data['employee_store_marital_status'],
                'children' => $data['employee_store_children'],
                'is_active' => $data['employee_store_is_active'],
                'employer_id' => $data['employee_store_employer'],
                'department_id' => $data['employee_store_department'],

            ];

            $newEmployee = $this->employeesRepository->create($createData);
            $condition = [
                ['id', "=", $createData['employer_id']],
                ['company_status', "=", 1],
                ['payroll_status', "=", 1]
            ];

            $employer = $this->employersRepository->firstWhere($condition);

            if (empty($employer)) {
                return redirect()->route('employee.index')->withError('Add error. Employer not found.');
            }
            //TODO: https://gitlab.2nf.com.vn/2019/we-way-server/issues/12
            $this->monthlySalaryRepository->create([
                'employee_id' => $newEmployee->id,
                'employer_id' => $createData['employer_id'],
                'year_month' => date('Ym'),
                'payroll_payment_date' => $employer->payroll_payment_date,
                'advance_all_salary_date' => $employer->advance_all_salary_date,
                'advance_date_adjustment' => $employer->advance_date_adjustment,
                'fee_tariff' => $employer->fee_tariff,
            ]);

            return redirect()->route('employee.index')->withSuccess('Add successfully.');
        }

        //check permission
        if (!$currentEmployeeId || !is_numeric($currentEmployeeId)) {
            return redirect()->route('employee.index')->withError('Add error. Employee not found.');
        }

        $condition = [
            ['id', "=", $currentEmployeeId]
        ];
        $employee = $this->employeesRepository->firstWhere($condition);

        if (!$employee) {
            return redirect()->route('employee.index')->withError('Add error. Employee not found.');
        }


        $updateData = [
            'weway_customer_number' => $data['employee_store_weway_customer_number'],
            'surname' => $data['employee_store_surname'],
            'middle_name' => $data['employee_store_middle_name'],
            'name' => $data['employee_store_name'],
            'mobile' => $data['employee_store_mobile'],
            'work_number' => $data['employee_store_work_number'],
            'home_number' => $data['employee_store_home_number'],
            'date_of_birth' => $data['employee_store_date_of_birth'],
            'work_email' => $data['employee_store_work_email'],
            'personal_email' => $data['employee_store_personal_email'],
            'joining_date' => $data['employee_store_joining_date'],
            'leaving_date' => $data['employee_store_leaving_date'],
            'bank_account' => $data['employee_store_bank_account'],
            'bank' => $data['employee_store_bank'],
            'employee_number' => $data['employee_store_employee_number'],
            'id_number' => $data['employee_store_id_number'],
            'address' => $data['employee_store_address'],
            'personal_tax_code' => $data['employee_store_personal_tax_code'],
            'facebook' => $data['employee_store_facebook'],
            'linkedin' => $data['employee_store_linkedin'],
            'education' => $data['employee_store_education'],
            'status' => $data['employee_store_status'],
            'gender' => $data['employee_store_gender'],
            'marital_status' => $data['employee_store_marital_status'],
            'children' => $data['employee_store_children'],
            'is_active' => $data['employee_store_is_active'],
            'employer_id' => $data['employee_store_employer'],
            'department_id' => $data['employee_store_department'],
        ];

        $condition = [
            ['id', "=", $data['employee_store_employer']],
            ['company_status', "=", 1],
            ['payroll_status', "=", 1]
        ];

        $employer = $this->employersRepository->firstWhere($condition);

        if (empty($employer)) {
            return redirect()->route('employee.index')->withError('Add error. Employer not found.');
        }

        $this->employeesRepository->update($updateData, $employee->id);

        $monthlySalary = $this->monthlySalaryRepository->firstWhere([
            'employee_id' => $employee->id,
            'employer_id' => $data['employee_store_employer'],
            'year_month' => date('Ym')
        ]);

        if (empty($monthlySalary)) {
            //TODO: https://gitlab.2nf.com.vn/2019/we-way-server/issues/12
            $this->monthlySalaryRepository->create([
                'employee_id' => $employee->id,
                'employer_id' => $employer->id,
                'year_month' => date('Ym'),
                'payroll_payment_date' => $employer->payroll_payment_date,
                'advance_all_salary_date' => $employer->advance_all_salary_date,
                'advance_date_adjustment' => $employer->advance_date_adjustment,
                'fee_tariff' => $employer->fee_tariff,
            ]);
        }

        return redirect()->route('employee.index')->withSuccess('Update successfully.');
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