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
                        <form class="form-horizontal" method="POST" action="{{ route('employee.index') }}" id="search_form">
                            @csrf
                            <input type="hidden" value="{{ isset($employeesData['searchValue']['sort']) ? $employeesData['searchValue']['sort'] : 'desc' }}" name="sort">
                            <input type="hidden" value="{{ isset($employeesData['searchValue']['order']) ? $employeesData['searchValue']['order'] : 'created_at' }}" name="order" id="order">
                            <div class="box-body">
                                <table class="col-md-12">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Name
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control custom-table-margin" name="employee_name" id="employee_name" value="{{$employeesData['searchValue']['employee_name'] != null ? $employeesData['searchValue']['employee_name'] : old('employee_name')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Mobile
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="employee_phone" id="employee_phone" class="form-control custom-table-margin" value="{{$employeesData['searchValue']['employee_phone'] != null ? $employeesData['searchValue']['employee_phone'] : old('employee_phone')}}">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Email
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="employee_mail" id="employee_mail" class="form-control custom-table-margin" value="{{$employeesData['searchValue']['employee_mail'] != null ? $employeesData['searchValue']['employee_mail'] : old('employee_mail')}}">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Employer
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="employee_employer" id="employee_employer" class="form-control custom-table-margin" value="{{$employeesData['searchValue']['employee_employer'] != null ? $employeesData['searchValue']['employee_employer'] : old('employee_employer')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Status
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-2">
                                                    @php $employee_status_is_enabled = $employeesData['searchValue']['employee_status'];@endphp
                                                    <input type="radio" class="custom-table-margin" name="employee_status" id="has_status_all" value="" @if($employee_status_is_enabled==null || old('employee_status') == null) checked @endif>
                                                    ALL
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" type="radio" name="employee_status" id="has_status_current" value="1" @if($employee_status_is_enabled==1 || old('employee_status') == 1) checked @endif>
                                                    Current
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" type="radio" name="employee_status" id="has_status_suspended" value="2" @if($employee_status_is_enabled==2 || old('employee_status') == 2) checked @endif>
                                                    Suspended
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" type="radio" name="employee_status" id="has_status_left" value="3" @if($employee_status_is_enabled==3 || old('employee_status') == 3) checked @endif>
                                                    Left
                                                </label>
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
                            <div class="custom-table-msg alert alert-success alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{session('success')}}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="custom-table-msg alert alert-danger alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{session('error')}}
                            </div>
                        @endif
                        @if(!$employeesData["employees"])
                            <div class="custom-table-msg">
                                No data
                            </div>
                        @endif
                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Employees list</h3>
                        </div>
                        @if($employeesData["employees"])
                            <div class="box-body">
                                <table id="sortable" class="col-md-12 table table-bordered table-striped table-hover" style="min-width: 1400px">
                                    <thead>
                                    <tr style="background-color: #D3D3D3;">
                                        <th></th>
                                        <th>WeWay Customer Number</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Date of Birth</th>
                                        <th>Work Email</th>
                                        <th>Personal Email</th>
                                        <th>Employer</th>
                                        <th>Department</th>
                                        <th>Joining date</th>
                                        <th>Leaving date</th>
                                        <th>Status</th>
                                        <th>Bank account</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($employeesData["employees"] as $key => $employee)
                                        <tr class="user" onclick="clickCheckbox({{$key}})">
                                            <td align="center">
                                                <label class="container-radio">
                                                    <input name="confirm_change" value="{{ $employee->id }}" type="checkbox" id="click-box-{{$key}}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </td>
                                            <td>{{ $employee->weway_customer_number}}</td>
                                            <td class="txt-left table-cut">{{ $employee->surname . ' ' . $employee->middle_name . ' ' . $employee->name }}</td>
                                            <td>{{ $employee->mobile }}</td>
                                            <td>{{ $employee->date_of_birth }}</td>
                                            <td>{{ $employee->work_email }}</td>
                                            <td>{{ $employee->personal_email }}</td>
                                            <td>{{ $employee->employer_name }}</td>
                                            <td>{{ $employee->department }}</td>
                                            <td>{{ $employee->joining_date }}</td>
                                            <td>{{ $employee->leaving_date == null || $employee->leaving_date == '' ? '--' : $employee->leaving_date }}</td>
                                            <td>
                                                @php
                                                    $listStatus = [
                                                    '1' => 'Current',
                                                    '2' => 'Suspended',
                                                    '3' => 'Left',
                                                    ];
                                                    if(array_key_exists($employee->status, $listStatus)) {
                                                        $generalStatus = $listStatus[$employee->status];
                                                    } else {
                                                        $generalStatus = "";
                                                    }
                                                @endphp
                                                {{ $generalStatus }}
                                            </td>
                                            <td>{{ $employee->bank_account }}</td>
                                            <td><a href="{{ route('employee-payroll.index', $employee->id) }}">Employee Payroll</a></td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="row custom-footer-list">
                            <div class="col-sm-3">
                                <button onclick="getIdEmployeeEdit()" class="btn-custom btn-default pull-left margin-5px">Update</button>
                            </div>
                            <div class="col-sm-9" style="float: right">
                                @php
                                    $page = $employeesData["page"];
                                @endphp
                                @include('admin.common.paging')
                            </div>
                        </div>
                    </div>

                    <div id="form-edit">
                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Employee management renewal</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" id="form-create-or-update" method="POST" action="{{ route('employee.store') }}">
                                @csrf
                                <input name="employee_store_id" value="{{old('_token') ? old('employee_store_id') : ""}}" type="hidden" id="employee_store_id">
                                <table class="col-md-8">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Weway customer number <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_weway_customer_number') : ""}}" class="form-control custom-table-margin" name="employee_store_weway_customer_number">
                                                @if ($errors->has('employee_store_weway_customer_number'))
                                                    <p class="is-error">{{ $errors->first('employee_store_weway_customer_number') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Surname <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_surname') : ""}}" class="form-control custom-table-margin" name="employee_store_surname">
                                                @if ($errors->has('employee_store_surname'))
                                                    <p class="is-error">{{ $errors->first('employee_store_surname') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Middle Name
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_middle_name') : ""}}" class="form-control custom-table-margin" name="employee_store_middle_name">
                                                @if ($errors->has('employee_store_middle_name'))
                                                    <p class="is-error">{{ $errors->first('employee_store_middle_name') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Name <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_name') : ""}}" class="form-control custom-table-margin" name="employee_store_name">
                                                @if ($errors->has('employee_store_name'))
                                                    <p class="is-error">{{ $errors->first('employee_store_name') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Mobile <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_mobile') : ""}}" class="form-control custom-table-margin" name="employee_store_mobile">
                                                @if ($errors->has('employee_store_mobile'))
                                                    <p class="is-error">{{ $errors->first('employee_store_mobile') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Work Number <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_work_number') : ""}}" class="form-control custom-table-margin" name="employee_store_work_number">
                                                @if ($errors->has('employee_store_work_number'))
                                                    <p class="is-error">{{ $errors->first('employee_store_work_number') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Home Number <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_home_number') : ""}}" class="form-control custom-table-margin" name="employee_store_home_number">
                                                @if ($errors->has('employee_store_home_number'))
                                                    <p class="is-error">{{ $errors->first('employee_store_home_number') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Date of Birth <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" data-toggle="datepicker" autocomplete="off" value="{{old('_token') ? old('employee_store_date_of_birth') : ""}}" class="form-control custom-table-margin" name="employee_store_date_of_birth">
                                                @if ($errors->has('employee_store_date_of_birth'))
                                                    <p class="is-error">{{ $errors->first('employee_store_date_of_birth') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Work Email<span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_work_email') : ""}}" class="form-control custom-table-margin" name="employee_store_work_email">
                                                @if ($errors->has('employee_store_work_email'))
                                                    <p class="is-error">{{ $errors->first('employee_store_work_email') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Personal Email <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employee_store_personal_email') : ""}}" class="form-control custom-table-margin" name="employee_store_personal_email">
                                                @if ($errors->has('employee_store_personal_email'))
                                                    <p class="is-error">{{ $errors->first('employee_store_personal_email') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Joining Date <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" data-toggle="datepicker" autocomplete="off" value="{{old('_token') ? old('employee_store_joining_date') : ""}}" class="form-control custom-table-margin" name="employee_store_joining_date">
                                                @if ($errors->has('employee_store_joining_date'))
                                                    <p class="is-error">{{ $errors->first('employee_store_joining_date') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Leaving Date
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" data-toggle="datepicker" autocomplete="off" value="{{old('_token') ? old('employee_store_leaving_date') : ""}}" class="form-control custom-table-margin" name="employee_store_leaving_date">
                                                @if ($errors->has('employee_store_leaving_date'))
                                                    <p class="is-error">{{ $errors->first('employee_store_leaving_date') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Status <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employee_store_status_1" type="radio" name="employee_store_status" @if(!old('_token') || old('employee_store_status') == 1) checked @endif>
                                                    Current
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_status_2" name="employee_store_status" value="2" @if(old('_token') && old('employee_store_status') == 2) checked @endif>
                                                    Suspended
                                                </label>
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_status_3" name="employee_store_status" value="3" @if(old('_token') && old('employee_store_status') == 3) checked @endif>
                                                    Left
                                                </label>
                                                @if ($errors->has('employee_store_status'))
                                                    <p class="is-error">{{ $errors->first('employee_store_status') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Bank Account <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_bank_account') : ""}}" class="form-control custom-table-margin" name="employee_store_bank_account">
                                                @if ($errors->has('employee_store_bank_account'))
                                                    <p class="is-error">{{ $errors->first('employee_store_bank_account') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Bank <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_bank') : ""}}" class="form-control custom-table-margin" name="employee_store_bank">
                                                @if ($errors->has('employee_store_bank'))
                                                    <p class="is-error">{{ $errors->first('employee_store_bank') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Employer <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <select class="form-control custom-table-margin" onchange="getIdDepartmentByEmployerId()" style="height: 31px; padding-bottom: 3px; padding-top: 3px;" id="employee_store_employer" name="employee_store_employer">
                                                    <option value="">Choose an employer</option>
                                                    @if(!empty($employeesData["employers"]))
                                                        @foreach ($employeesData["employers"] as $key => $employer)
                                                            <option value="{{$employer->id}}" {{ old('_token') && old('employee_store_employer') == $employer->id ? 'selected' : ''}}>{{$employer->company}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if ($errors->has('employee_store_employer'))
                                                    <p class="is-error">{{ $errors->first('employee_store_employer') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Employee Number
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_employee_number') : ""}}" name="employee_store_employee_number" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_employee_number'))
                                                    <p class="is-error">{{ $errors->first('employee_store_employee_number') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Department
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <select class="form-control custom-table-margin" style="height: 31px; padding-bottom: 3px; padding-top: 3px;" id="employee_store_department" name="employee_store_department">
                                                    <option value="">Choose a department</option>
                                                </select>
                                                @if ($errors->has('employee_store_department'))
                                                    <p class="is-error">{{ $errors->first('employee_store_department') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            ID Number
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_id_number') : ""}}" name="employee_store_id_number" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_id_number'))
                                                    <p class="is-error">{{ $errors->first('employee_store_id_number') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Address
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_address') : ""}}" name="employee_store_address" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_address'))
                                                    <p class="is-error">{{ $errors->first('employee_store_address') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Personal Tax Code
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_personal_tax_code') : ""}}" name="employee_store_personal_tax_code" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_personal_tax_code'))
                                                    <p class="is-error">{{ $errors->first('employee_store_personal_tax_code') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Facebook
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_facebook') : ""}}" name="employee_store_facebook" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_facebook'))
                                                    <p class="is-error">{{ $errors->first('employee_store_facebook') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Linkedin
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_linkedin') : ""}}" name="employee_store_linkedin" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_linkedin'))
                                                    <p class="is-error">{{ $errors->first('employee_store_linkedin') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Gender
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employee_store_gender_1" type="radio" name="employee_store_gender" @if(!old('_token') || old('employee_store_gender') == 1) checked @endif>
                                                    Male
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_gender_2" name="employee_store_gender" value="2" @if(old('_token') && old('employee_store_gender') == 2) checked @endif>
                                                    Female
                                                </label>
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_gender_3" name="employee_store_gender" value="3" @if(old('_token') && old('employee_store_gender') == 3) checked @endif>
                                                    Other
                                                </label>
                                                @if ($errors->has('employee_store_gender'))
                                                    <p class="is-error">{{ $errors->first('employee_store_gender') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Marital Status
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employee_store_marital_status_1" type="radio" name="employee_store_marital_status" @if(!old('_token') || old('employee_store_marital_status') == 1) checked @endif>
                                                    Married
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_marital_status_0" name="employee_store_marital_status" value="0" @if(old('_token') && old('employee_store_marital_status') == 0) checked @endif>
                                                    Single
                                                </label>
                                                @if ($errors->has('employee_store_marital_status'))
                                                    <p class="is-error">{{ $errors->first('employee_store_marital_status') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Children
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employee_store_children_1" type="radio" name="employee_store_children" @if(!old('_token') || old('employee_store_children') == 1) checked @endif>
                                                    Yes
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_children_0" name="employee_store_children" value="0" @if(old('_token') && old('employee_store_children') == 0) checked @endif>
                                                    No
                                                </label>
                                                @if ($errors->has('employee_store_children'))
                                                    <p class="is-error">{{ $errors->first('employee_store_children') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Education
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('_token') ? old('employee_store_education') : ""}}" name="employee_store_education" class="form-control custom-table-margin">
                                                @if ($errors->has('employee_store_education'))
                                                    <p class="is-error">{{ $errors->first('employee_store_education') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Active <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employee_store_is_active_1" type="radio" name="employee_store_is_active" @if(!old('_token') || old('employee_store_is_active') == 1) checked @endif>
                                                    Yes
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employee_store_is_active_0" name="employee_store_is_active" value="0" @if(old('_token') && old('employee_store_is_active') == 0) checked @endif>
                                                    No
                                                </label>
                                                @if ($errors->has('employee_store_is_active'))
                                                    <p class="is-error">{{ $errors->first('employee_store_is_active') }}</p>
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

        function addNew() {
            $('html, body').animate({
                scrollTop: ($('#form-edit').offset().top - 60)
            }, 1000);

            $('.is-error').empty();

            $('input[name=employee_store_weway_customer_number]').val('');
            $('input[name=employee_store_surname]').val('');
            $('input[name=employee_store_middle_name]').val('');
            $('input[name=employee_store_name]').val('');
            $('input[name=employee_store_mobile]').val('');
            $('input[name=employee_store_work_number]').val('');
            $('input[name=employee_store_home_number]').val('');
            $('input[name=employee_store_date_of_birth]').val('');
            $('input[name=employee_store_work_email]').val('');
            $('input[name=employee_store_personal_email]').val('');
            $('input[name=employee_store_joining_date]').val('');
            $('input[name=employee_store_leaving_date]').val('');
            $('input[name=employee_store_id]').val('');
            $('input[name=employee_store_bank_account]').val('');
            $('input[name=employee_store_bank]').val('');
            $('input[name=employee_store_employee_number]').val('');
            $('input[name=employee_store_id_number]').val('');
            $('input[name=employee_store_address]').val('');
            $('input[name=employee_store_personal_tax_code]').val('');
            $('input[name=employee_store_facebook]').val('');
            $('input[name=employee_store_linkedin]').val('');
            $('input[name=employee_store_education]').val('');
            $('#employee_store_status_1').prop('checked', true);
            $('#employee_store_gender_1').prop('checked', true);
            $('#employee_store_marital_status_0').prop('checked', true);
            $('#employee_store_children_0').prop('checked', true);
            $('#employee_store_is_active_0').prop('checked', true);
            document.getElementById('employee_store_employer').value = "";
            document.getElementById('employee_store_department').value = "";
        }

        function getIdDepartmentByEmployerId() {
            var e = document.getElementById("employee_store_employer");
            var employerId = e.options[e.selectedIndex].value;
            var url = '{{ route("employee.getDepartmentsByEmployerId", ":id") }}';
            url = url.replace(':id', employerId);
            if (employerId != "") {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                }).done(function (data) {

                    var d = document.getElementById("employee_store_department");
                    var i;
                    for(i = d.options.length - 1 ; i >= 0 ; i--)
                    {
                        d.remove(i);
                    }

                    $('#employee_store_department')
                        .append($("<option></option>")
                            .attr("value", "")
                            .text("Choose a department"));

                    $.each(data.employeesData.departments, function(key, value) {
                        $('#employee_store_department')
                            .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.department));
                    });
                });
            } else {
                var d = document.getElementById("employee_store_department");
                var i;
                for(i = d.options.length - 1 ; i >= 0 ; i--)
                {
                    d.remove(i);
                }

                $('#employee_store_department')
                    .append($("<option></option>")
                        .attr("value", "")
                        .text("Choose a department"));
            }

        }

        function getIdEmployeeEdit() {

            $('html, body').animate({
                scrollTop: ($('#form-edit').offset().top - 60)
            }, 1000);

            var employeeId = $('input[name=confirm_change]:checked').val();
            var url = '{{ route("employee.edit", ":id") }}';
            url = url.replace(':id', employeeId);

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
            }).done(function (data) {
                $('.is-error').empty();

                $('input[name=employee_store_weway_customer_number]').val(data.employeesData.employee.weway_customer_number);
                $('input[name=employee_store_surname]').val(data.employeesData.employee.surname);
                $('input[name=employee_store_middle_name]').val(data.employeesData.employee.middle_name);
                $('input[name=employee_store_name]').val(data.employeesData.employee.name);
                $('input[name=employee_store_mobile]').val(data.employeesData.employee.mobile);
                $('input[name=employee_store_work_number]').val(data.employeesData.employee.work_number);
                $('input[name=employee_store_home_number]').val(data.employeesData.employee.home_number);
                $('input[name=employee_store_date_of_birth]').val(data.employeesData.employee.date_of_birth);
                $('input[name=employee_store_work_email]').val(data.employeesData.employee.work_email);
                $('input[name=employee_store_personal_email]').val(data.employeesData.employee.personal_email);
                $('input[name=employee_store_joining_date]').val(data.employeesData.employee.joining_date);
                $('input[name=employee_store_leaving_date]').val(data.employeesData.employee.leaving_date);
                $('input[name=employee_store_id]').val(data.employeesData.employee.id);
                $('input[name=employee_store_bank_account]').val(data.employeesData.employee.bank_account);
                $('input[name=employee_store_bank]').val(data.employeesData.employee.bank);
                $('input[name=employee_store_employee_number]').val(data.employeesData.employee.employee_number);
                $('input[name=employee_store_id_number]').val(data.employeesData.employee.id_number);
                $('input[name=employee_store_address]').val(data.employeesData.employee.address);
                $('input[name=employee_store_personal_tax_code]').val(data.employeesData.employee.personal_tax_code);
                $('input[name=employee_store_facebook]').val(data.employeesData.employee.facebook);
                $('input[name=employee_store_linkedin]').val(data.employeesData.employee.linkedin);
                $('input[name=employee_store_education]').val(data.employeesData.employee.education);

                document.getElementById('employee_store_employer').value = data.employeesData.employee.employer_id ? data.employeesData.employee.employer_id : "";

                if (data.employeesData.employee.status == 1) {
                    $('#employee_store_status_1').prop('checked', true);
                    $('#employee_store_status_2').prop('checked', false);
                    $('#employee_store_status_3').prop('checked', false);
                } else if (data.employeesData.employee.status == 2) {
                    $('#employee_store_status_1').prop('checked', false);
                    $('#employee_store_status_2').prop('checked', true);
                    $('#employee_store_status_3').prop('checked', false);

                } else if (data.employeesData.employee.status == 3) {
                    $('#employee_store_status_1').prop('checked', false);
                    $('#employee_store_status_2').prop('checked', false);
                    $('#employee_store_status_3').prop('checked', true);
                }

                if (data.employeesData.employee.gender == 1) {
                    $('#employee_store_gender_1').prop('checked', true);
                    $('#employee_store_gender_2').prop('checked', false);
                    $('#employee_store_gender_3').prop('checked', false);
                } else if (data.employeesData.employee.gender == 2) {
                    $('#employee_store_gender_1').prop('checked', false);
                    $('#employee_store_gender_2').prop('checked', true);
                    $('#employee_store_gender_3').prop('checked', false);

                } else if (data.employeesData.employee.gender == 3) {
                    $('#employee_store_gender_1').prop('checked', false);
                    $('#employee_store_gender_2').prop('checked', false);
                    $('#employee_store_gender_3').prop('checked', true);
                }

                if (data.employeesData.employee.marital_status == 1) {
                    $('#employee_store_marital_status_1').prop('checked', true);
                    $('#employee_store_marital_status_0').prop('checked', false);
                } else if (data.employeesData.employee.marital_status == 0) {
                    $('#employee_store_marital_status_1').prop('checked', false);
                    $('#employee_store_marital_status_0').prop('checked', true);
                }

                if (data.employeesData.employee.children == 1) {
                    $('#employee_store_children_1').prop('checked', true);
                    $('#employee_store_children_0').prop('checked', false);
                } else if (data.employeesData.employee.children == 0) {
                    $('#employee_store_children_1').prop('checked', false);
                    $('#employee_store_children_0').prop('checked', true);
                }

                if (data.employeesData.employee.is_active == 1) {
                    $('#employee_store_is_active_1').prop('checked', true);
                    $('#employee_store_is_active_0').prop('checked', false);
                } else if (data.employeesData.employee.is_active == 0) {
                    $('#employee_store_is_active_1').prop('checked', false);
                    $('#employee_store_is_active_0').prop('checked', true);
                }

                var d = document.getElementById("employee_store_department");
                var i;
                for(i = d.options.length - 1 ; i >= 0 ; i--)
                {
                    d.remove(i);
                }

                $('#employee_store_department')
                    .append($("<option></option>")
                        .attr("value", "")
                        .text("Choose a department"));

                $.each(data.employeesData.departments, function(key, value) {
                    $('#employee_store_department')
                        .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.department));
                });

                document.getElementById('employee_store_department').value = data.employeesData.employee.department_id ? data.employeesData.employee.department_id : "";

            });
        }

        if ($('.is-error').length > 0) {
            $('html, body').animate({
                scrollTop: ($('#form-create-or-update').offset().top - 300)
            }, 1000);
        }

        $(document).ready(function() {
            window.objectUserDataOld = JSON.parse('<?php echo json_encode(isset($employeesData['searchValue']) ? $employeesData['searchValue'] : []); ?>');

            $('[data-toggle="tooltip"]').tooltip({
                'container':'body'
            });

            $.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );

            $('input[data-toggle="datepicker"]').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
@endsection

@section('css_bottom')
@endsection
