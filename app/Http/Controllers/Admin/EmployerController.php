<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Repository\Eloquent\EmployersRepository;
use App\Repository\Eloquent\MonthlySalaryRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class EmployerController extends AdminBaseController
{
    protected $employersRepository;
    protected $monthlySalaryRepository;

    public function __construct(EmployersRepository $employersRepository, MonthlySalaryRepository $monthlySalaryRepository)
    {
        $this->monthlySalaryRepository = $monthlySalaryRepository;
        $this->employersRepository = $employersRepository;
    }
    public function index(Request $request){
        $searchData = $request->all();
        $currentPage =  isset($searchData['page']) ? $searchData['page'] : 1;

        if (!$currentPage || !is_numeric($currentPage) || $currentPage < 1) {
            $currentPage = 1;
        }

        // Record counts in a page.
        $numberPerPage = config('constants.NUMBER_PERPAGE');

        if (!is_array($searchData)) {
            $searchData = [];
        }
        $total = $this->employersRepository->countEmployer($searchData);
        
        $totalPage = ceil($total / $numberPerPage);
        if ($currentPage > $totalPage) {
            $currentPage = $totalPage;
        }

        $offset = ($currentPage - 1) * $numberPerPage;
        $orderBy = [];
        $order = isset($searchData['order']) ? $searchData['order'] : 'id';
        $sort = isset($searchData['sort']) ? $searchData['sort'] : 'desc';
        $orderBy = [$order, $sort];
        $employers = $this->employersRepository->searchWithEmployer($numberPerPage, $offset, $searchData, $orderBy);
        $searchValue = [
                'employer_code' => isset($searchData['employer_code']) ? $searchData['employer_code'] : null,
                'employer_company' => isset($searchData['employer_company']) ? $searchData['employer_company'] : null,
                'employer_vat' => isset($searchData['employer_vat']) ? $searchData['employer_vat'] : null,
                'employer_address' => isset($searchData['employer_address']) ? $searchData['employer_address'] : null,
                'employer_website' => isset($searchData['employer_website']) ? $searchData['employer_website'] : null,
                'employer-company_status' => isset($searchData['employer-company_status']) ? $searchData['employer-company_status'] : '',
                'employer-payroll_status' => isset($searchData['employer-payroll_status']) ? $searchData['employer-payroll_status'] : '',
                'sort' => $sort,
                'order' => $order
        ];
        $page = [
                "total" => $total,
                "totalPage" => $totalPage,
                "currentPage" => $currentPage,
        ];
        return view('admin.employer.index', compact(['employers', 'searchValue', 'page']));
    }
    public function store(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'employer_store_code' => 'required',
            'employer_store_company' => 'required',
            'employer_store_vat' => 'required',
            'employer_store_firstContact' => 'required',
            // 'employer_store_fcTitle' => 'required',
            'employer_store_fcNumber' => 'required',
            // 'employer_store_secondContact' => 'required',
            // 'employer_store_scTitle' => 'required',
            // 'employer_store_scNumber' => 'required',
            'employer_store_address' => 'required',
            'employer_store_taxCode' => 'required',
            'employer_store_website' => 'required',
            'employer_store_payrollDate' => 'required',
            'employer_store_advanceAll' => 'required',
            'employer_store_advanceDate' => 'required',
            'employer_store_advance_percentage' => 'required',
            'employer_store_fee_tariff' => 'required',
        ])->validate();
        try {
            $employer_update = [
                'code' => $data['employer_store_code'],
                'company' => $data['employer_store_company'],
                'vat_number' => $data['employer_store_vat'],
                'first_contact' => $data['employer_store_firstContact'],
                'fc_title' => $data['employer_store_fcTitle'],
                'fc_number' => $data['employer_store_fcNumber'],
                'second_contact' => $data['employer_store_secondContact'],
                'sc_title' => $data['employer_store_scTitle'],
                'sc_number' => $data['employer_store_scNumber'],
                'address' => $data['employer_store_address'],
                'tax_code' => $data['employer_store_taxCode'],
                'website' => $data['employer_store_website'],
                'payroll_payment_date' => $data['employer_store_payrollDate'],
                'advance_all_salary_date' => $data['employer_store_advanceAll'],
                'advance_date_adjustment' => $data['employer_store_advanceDate'],
                'fee_tariff' => $data['employer_store_fee_tariff'],
                'advance_percentage' => $data['employer_store_advance_percentage'],
                'company_status' => $data['employer_store_company_status'],
                'payroll_status' => $data['employer_store_payroll_status'],
            ];
            if(isset($data['employer_store_id']) && $data['employer_store_id'] != null){
                // update employer table
                $this->employersRepository->update($employer_update, $data['employer_store_id']);
                // update month_salary table
                $month  = date("Ym");
                $arrClause = [
                    ['employer_id', 1],
                    ['year_month', $month]
                ];
                $arrUpdate = [
                    'payroll_payment_date' => $data['employer_store_payrollDate'],
                    'advance_all_salary_date' => $data['employer_store_advanceAll'],
                    'advance_date_adjustment' => $data['employer_store_advanceDate'],
                    'fee_tariff' => $data['employer_store_fee_tariff'],
                    'advance_percentage' => $data['employer_store_advance_percentage'],
                ];
                $this->monthlySalaryRepository->updateMultipleRows($arrClause, $arrUpdate);
                return redirect()->route('employer.index')->withSuccess('Update successfully.');
            }
            else{
                $this->employersRepository->create($employer_update);
                
                return redirect()->route('employer.index')->withSuccess('Add successfully.');
            }
        }
        catch(\Exception $e){
            return redirect()->route('employer.index')->withError('Add error. Employer not found.');
        }
        
    }
}
