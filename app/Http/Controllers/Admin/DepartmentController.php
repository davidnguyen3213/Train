<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repository\Eloquent\DepartmentsRepository;
use Validator;

class DepartmentController extends AdminBaseController
{
    protected $DepartmentsRepository;


    /**
     * UserController constructor.
     * @param DepartmentsRepository $DepartmentsRepository
     */
    public function __construct(
        DepartmentsRepository $departmentsRepository
    )
    {
        $this->departmentsRepository = $departmentsRepository;
    }

    public function index(Request $request)
    {
        $id_employer = $request->id;
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
        $total = $this->departmentsRepository->countDepartments($id_employer, $searchData);

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
        // , $numberPerPage, $offset, $searchData, $orderBy

        $departments = $this->departmentsRepository->searchWithDepartment($id_employer, $numberPerPage, $offset, $searchData, $orderBy);

        $searchValue = [
            'department_code' => isset($searchData['department_code']) ? $searchData['department_code'] : null,
            'department_parent_division' => isset($searchData['department_parent_division']) ? $searchData['department_parent_division'] : null,
            'department_hod' => isset($searchData['department_hod']) ? $searchData['department_hod'] : null,
            'department_hod_mobile' => isset($searchData['department_hod_mobile']) ? $searchData['department_hod_mobile'] : null,
            'department_hod_office_number' => isset($searchData['department_hod_office_number']) ? $searchData['department_hod_office_number'] : null,
            'department_hod_email' => isset($searchData['department_hod_email']) ? $searchData['department_hod_email'] : null,
            'sort' => $sort,
            'order' => $order
        ];
        $page = [
            "total" => $total,
            "totalPage" => $totalPage,
            "currentPage" => $currentPage,
        ];
        return view('admin.department.index', compact(['id_employer','departments', 'searchValue','page']));
    }
    public function store(Request $request){
        $id_employer = $request->id;
        $data = $request->all();
        Validator::make($data, [
            'parent_division' => 'required',
            'department' => 'required',
            'head_of_department' => 'required',
            'hod_mobile'=> 'required',
            'hod_office_number' => 'required',
            'hod_email' => 'required|email',
        ])->validate();
        try {
            $department_update = [
                'employer_id' => $id_employer,
                'parent_division' => $data['parent_division'],
                'department' => $data['department'],
                'head_of_department' => $data['head_of_department'],
                'hod_mobile' => $data['hod_mobile'],
                'hod_office_number' => $data['hod_office_number'],
                'hod_email' => $data['hod_email'],
            ];
            if (isset($data['department_id']) && $data['department_id'] != null) {
                $this->departmentsRepository->update($department_update, $data['department_id']);
                return redirect()->route('department.index',['id'=> $id_employer])->withSuccess('Update successfully.');
            } else {
                $this->departmentsRepository->create($department_update);
                return redirect()->route('department.index',['id'=> $id_employer])->withSuccess('Add successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->route('department.index', ['id' => $id_employer])->withError('Add error. Employer not found.');
        }
    }
}