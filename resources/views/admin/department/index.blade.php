@extends('admin.layout.app')
@section('javascript_head')
    <script>
        $(document).ready(function() {
            $('#sortable .sortable-item').click(function() {

                var currentSortingType = $(this).find('span').attr('class');

                if (currentSortingType === undefined || currentSortingType.trim() === 'ic-arrow-down') {
                    $('input[name="sort"]').val('asc');
                    $('input[name="order"]').val($(this).attr('id'));
                } else {
                    $('input[name="sort"]').val('desc');
                    $('input[name="order"]').val($(this).attr('id'));
                }

                $('#search_form').submit();
            });
        });
    </script>
@endsection

@section('css_head')
    <link rel="stylesheet" href="{{ asset('asset/css/common.css') }}">
@endsection

@section('header_page')
    <div class="custom-header-page">
        <h3 class="box-title custom-title">WE WAY</h3>
    </div>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                     <div class="box custom-box box-info" style="margin-bottom: 10px;">
                        <form class="form-horizontal" method="POST" action="{{ route('department.index',['id'=>$id_employer]) }}" id="search_form">
                            @csrf
                            <input type="hidden" value="{{ $searchValue['sort'] ? $searchValue['sort'] : 'desc' }}" name="sort">
                            <input type="hidden" value="{{ $searchValue['order'] ? $searchValue['order'] : 'id' }}" name="order" id="order">
                            <div class="box-body">
                                <table class="col-md-12">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Parent Division
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="department_parent_division" id="department_parent_division" class="form-control custom-table-margin" value="{{$searchValue['department_parent_division'] != null ? $searchValue['department_parent_division'] : old('department_parent_division')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Head of Department
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="department_hod" id="department_hod" class="form-control custom-table-margin" value="{{$searchValue['department_hod'] != null ? $searchValue['department_hod'] : old('department_hod')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Head of Department Mobile
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="department_hod_mobile" id="department_hod_mobile" class="form-control custom-table-margin" value="{{$searchValue['department_hod_mobile'] != null ? $searchValue['department_hod_mobile'] : old('department_hod_mobile')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Head of Department Office Number
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="department_hod_office_number" id="department_hod_office_number" class="form-control custom-table-margin" value="{{$searchValue['department_hod_office_number'] != null ? $searchValue['department_hod_office_number'] : old('department_hod_office_number')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Head of Department Email
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="department_hod_email" id="department_hod_email" class="form-control custom-table-margin" value="{{$searchValue['department_hod_email'] != null ? $searchValue['department_hod_email'] : old('department_hod_email')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header"></th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input name="page" type="hidden" value="1" id="search_page">
                                                <button type="submit" class="btn-custom btn-default pull-left margin-5px">Search</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="box-header ">
                        <button onclick="addNew()" class="btn-custom btn-default pull-left margin-5px">Add New</button>
                    </div>
                    <div class="custom-table-box">
                        @if(session('success'))
                            <div class="custom-table-msg alert alert-success alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{session('success')}}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="custom-table-msg alert alert-danger alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{session('error')}}
                            </div>
                        @endif
                        @if(!isset($departments))
                            <div class="custom-table-msg">
                                No data
                            </div>
                        @endif
                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Departments list</h3>
                        </div>
                         @if($departments)
                            <div class="box-body">
                                <table id="sortable" class="col-md-12 table table-bordered table-striped table-hover" style="min-width: 1400px">
                                    <thead>
                                    <tr style="background-color: #D3D3D3;">
                                        <th/>
                                        <th>Code</th>
                                        <th>Company</th>
                                        <th>Parent Division</th>
                                        <th class="d-none">Department</th>
                                        <th>Head of Department</th>
                                        <th>HoD Mobile</th>
                                        <th>HoD Office Number</th>
                                        <th>HoD Email</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($departments as $key => $department)
                                        <tr class="user" id="update-{{$department->id}}" onclick="clickCheckbox({{$key}})">
                                            <td align="center">
                                                <label class="container-radio">
                                                <input name="confirm_change" value="{{ $department->id }}" type="checkbox" id="click-box-{{$key}}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </td>
                                            <td>{{ $department->code}}</td>
                                            <td>{{ $department->company }}</td>
                                            <td>{{ $department->parent_division }}</td>
                                            <td class="d-none">{{ $department->department }}</td>
                                            <td>{{ $department->head_of_department }}</td>
                                            <td>{{ $department->hod_mobile }}</td>
                                            <td>{{ $department->hod_office_number }}</td>
                                            <td>{{ $department->hod_email }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="row custom-footer-list">
                            <div class="col-sm-3">
                                <button onclick="getIdDepartmentEdit()" class="btn-custom btn-default pull-left margin-5px">Update</button>
                            </div>
                            <div class="col-sm-9" style="float: right">
                                @include('admin.common.paging')
                            </div>
                        </div>
                    </div>
                    {{-- update and edit --}}
                    <div id="form-edit">
                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Employee management renewal</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" id="form-create-or-update" method="POST" action="{{ route('department.store',['id'=>$id_employer]) }}">
                                @csrf
                                <input name="department_id" class="addnew" value="{{old('_token') ? old('department_id') : ""}}" type="hidden" id="department_id">
                                <table class="col-md-8">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Parent Division <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('parent_division') : ""}}" class="form-control custom-table-margin addnew" name="parent_division">
                                                @if ($errors->has('parent_division'))
                                                    <p class="is-error">{{ $errors->first('parent_division') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Department <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('department') : ""}}" class="form-control custom-table-margin addnew" name="department">
                                                @if ($errors->has('department'))
                                                    <p class="is-error">{{ $errors->first('department') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Head of Department <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('head_of_department') : ""}}" class="form-control custom-table-margin addnew" name="head_of_department">
                                                @if ($errors->has('head_of_department'))
                                                    <p class="is-error">{{ $errors->first('head_of_department') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Head of Department Mobile <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('hod_mobile') : ""}}" class="form-control custom-table-margin addnew" name="hod_mobile">
                                                @if ($errors->has('hod_mobile'))
                                                    <p class="is-error">{{ $errors->first('hod_mobile') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Head of Department Office Number <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('hod_office_number') : ""}}" class="form-control custom-table-margin addnew" name="hod_office_number">
                                                @if ($errors->has('hod_office_number'))
                                                    <p class="is-error">{{ $errors->first('hod_office_number') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Head of Department Email <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('hod_email') : ""}}" class="form-control custom-table-margin addnew" name="hod_email">
                                                @if ($errors->has('hod_email'))
                                                    <p class="is-error">{{ $errors->first('hod_email') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create"></th>
                                        <td>
                                            <div class="col-sm-8">
                                                <button type="submit" id="btn-submit-form" class="btn-custom btn-default pull-left margin-5px">Save</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('javascript_bottom')
    <script>
        function pageing(page) {
            $('#search_page').val(page);
            var search_form = $("#search_form")
            resetDataOldFormSearch(window.objectUserDataOld, search_form)
            search_form.submit();
        }
        function addNew(){
            $('html, body').animate({
                scrollTop: ($('#form-edit').offset().top - 60)
            }, 1000);

            $('.is-error').empty();
            $("input.addnew").val('');
            $("#employer_store_company_status_1").prop("checked", true);
            $("#employer_store_payroll_status_1").prop("checked", true);
        }
        function getIdDepartmentEdit(){
            $('html, body').animate({
                scrollTop: ($('#form-edit').offset().top - 60)
            }, 1000);

            var department_id = $('input[name=confirm_change]:checked').val();
            var id = $("#update-"+department_id);
            data= {
                parent_division : id.find("td:eq(3)").html(),
                department : id.find("td:eq(4)").html(),
                head_of_department : id.find("td:eq(5)").html(),
                hod_mobile : id.find("td:eq(6)").html(),
                hod_office_number : id.find("td:eq(7)").html(),
                hod_email : id.find("td:eq(8)").html(),
            }
            // fill input
            $("input[name=department_id]").val(data.department_id);
            $("input[name=parent_division]").val(data.parent_division);
            $("input[name=department]").val(data.department);
            $("input[name=head_of_department]").val(data.head_of_department);
            $("input[name=hod_mobile]").val(data.hod_mobile);
            $("input[name=hod_office_number]").val(data.hod_office_number);
            $("input[name=hod_email]").val(data.hod_email);
        }
    </script>
@endsection
@section('css_bottom')
@endsection