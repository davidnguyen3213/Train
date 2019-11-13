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
                        <form class="form-horizontal" method="POST" action="{{ route('employer.index') }}" id="search_form">
                            @csrf
                            <input type="hidden" value="{{ $searchValue['sort'] ? $searchValue['sort'] : 'desc' }}" name="sort">
                            <input type="hidden" value="{{ $searchValue['order'] ? $searchValue['order'] : 'id' }}" name="order" id="order">
                            <div class="box-body">
                                <table class="col-md-12">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Code
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control custom-table-margin" name="employer_code" id="employer_code" value="{{$searchValue['employer_code'] != null ? $searchValue['employer_code'] : old('employer_code')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Company
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="employer_company" id="employer_company" class="form-control custom-table-margin" value="{{$searchValue['employer_company'] != null ? $searchValue['employer_company'] : old('employer_company')}}">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            VAT Number
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="number" name="employer_vat" id="employer_vat" class="form-control custom-table-margin" value="{{$searchValue['employer_vat'] != null ? $searchValue['employer_vat'] : old('employer_vat')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Address
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="employer_address" id="employer_address" class="form-control custom-table-margin" value="{{$searchValue['employer_address'] != null ? $searchValue['employer_firstContact'] : old('employer_firstContact')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Website
                                        </th>
                                        <td>
                                            <div class="col-sm-7">
                                                <input type="text" name="employer_website" id="employer_website" class="form-control custom-table-margin" value="{{$searchValue['employer_website'] != null ? $searchValue['employer_secondContact'] : old('employer_secondContact')}}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Company Status
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                 @php 
                                                    $employer_company_status_enabled = $searchValue['employer-company_status'];
                                                    $checkAll = ($searchValue['employer-company_status'] == '' || !isset($searchValue['employer-company_status'])) ? 'checked':'';
                                                    $checkCurrent = ($searchValue['employer-company_status'] == '1' && isset($searchValue['employer-company_status'])) ? 'checked':'';
                                                    $checkStop = ($searchValue['employer-company_status'] == '2' && isset($searchValue['employer-company_status'])) ? 'checked':'';
                                                @endphp
                                                <label class="col-sm-2">
                                                    <input type="radio" {{$checkAll}} class="custom-table-margin" name="employer-company_status" id="has_status_all" value="">
                                                    ALL
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" {{$checkCurrent}} type="radio" name="employer-company_status" id="has_status_current" value="1">
                                                    Current
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" {{$checkStop}} type="radio" name="employer-company_status" id="has_status_current" value="2">
                                                    Stop
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header">
                                            Payroll Status
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                 @php 
                                                    $employer_payroll_status_enabled = $searchValue['employer-payroll_status'];
                                                    $checkAll = ($searchValue['employer-payroll_status'] == '' || !isset($searchValue['employer-payroll_status'])) ? 'checked':'';
                                                    $checkCurrent = ($searchValue['employer-payroll_status'] == '1' && isset($searchValue['employer-payroll_status'])) ? 'checked':'';
                                                    $checkStop = ($searchValue['employer-payroll_status'] == '2' && isset($searchValue['employer-payroll_status'])) ? 'checked':'';
                                                @endphp
                                                <label class="col-sm-2">
                                                    <input type="radio" {{$checkAll}} class="custom-table-margin" name="employer-payroll_status" id="has_status_all" value="">
                                                    ALL
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" {{$checkCurrent}} type="radio" name="employer-payroll_status" id="has_status_current" value="1">
                                                    Current
                                                </label>

                                                <label class="col-sm-2">
                                                    <input class="custom-table-margin" {{$checkStop}} type="radio" name="employer-payroll_status" id="has_status_current" value="2">
                                                    Stop
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
                        @if(!isset($employers))
                            <div class="custom-table-msg">
                                No data
                            </div>
                        @endif
                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Employers list</h3>
                        </div>
                        @if($employers)
                            <div class="box-body">
                                <table id="sortable" class="col-md-12 table table-bordered table-striped table-hover" style="min-width: 1400px">
                                    <thead>
                                    <tr style="background-color: #D3D3D3;">
                                        <th></th>
                                        <th>Code</th>
                                        <th>Company</th>
                                        <th>VAT Number</th>
                                        <th class="d-none">First Contact</th>
                                        <th class="d-none">FC Title</th>
                                        <th class="d-none">FC Number Email</th>
                                        <th class="d-none">Second Contact</th>
                                        <th class="d-none">SC Title</th>
                                        <th class="d-none">SC Number</th>
                                        <th>Address</th>
                                        <th class="d-none">Tax Cpde</th>
                                        <th>Website</th>
                                        <th class="d-none">Payroll payment date</th>
                                        <th class="d-none">Advance date adjustment</th>
                                        <th class="d-none">Advance Percentage</th>
                                        <th>Company status</th>
                                        <th>Payroll status</th>
                                        <th>Fee Tariff</th>
                                        <th class="d-none">Advance All Salary</th>
                                        <th/>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($employers as $key => $employer)
                                        <tr class="user" id="update-{{$employer->id}}" onclick="clickCheckbox({{$key}})">
                                            <td align="center">
                                                <label class="container-radio">
                                                <input name="confirm_change" value="{{ $employer->id }}" type="checkbox" id="click-box-{{$key}}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </td>
                                            <td>{{ $employer->code}}</td>
                                            <td>{{ $employer->company }}</td>
                                            <td>{{ $employer->vat_number }}</td>
                                            <td class="d-none">{{ $employer->first_contact }}</td>
                                            <td class="d-none">{{ $employer->fc_title }}</td>
                                            <td class="d-none">{{ $employer->fc_number }}</td>
                                            <td class="d-none">{{ $employer->second_contact }}</td>
                                            <td class="d-none">{{ $employer->sc_title }}</td>
                                            <td class="d-none">{{ $employer->sc_number }}</td>
                                            <td>{{ $employer->address }}</td>
                                            <td class="d-none">{{ $employer->tax_code }}</td>
                                            <td>{{ $employer->website }}</td>
                                            <td class="d-none">{{ $employer->payroll_payment_date }}</td>
                                            <td class="d-none">{{ $employer->advance_date_adjustment }}</td>
                                            <td class="d-none">{{ $employer->advance_percentage }}</td>
                                            @php 
                                                $status_config = config('constants.STATUS_EMPLOYER'); 
                                            @endphp
                                            <td>{{ $status_config[$employer->company_status]  }}</td>
                                            <td>{{ $status_config[$employer->payroll_status] }}</td>
                                            <td>{{ $employer->fee_tariff }}</td>
                                            <td class="d-none">{{ $employer->advance_all_salary_date }}</td>
                                            <td> <a href="{{route('department.index',['id'=> $employer->id])}}">department</a> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="row custom-footer-list">
                            <div class="col-sm-3">
                                <button onclick="getIdemployerEdit()" class="btn-custom btn-default pull-left margin-5px">Update</button>
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
                            <form class="form-horizontal" id="form-create-or-update" method="POST" action="{{ route('employer.store') }}">
                                @csrf
                                <input name="employer_store_id" class="addnew" value="{{old('_token') ? old('employer_store_id') : ""}}" type="hidden" id="employer_store_id">
                                <table class="col-md-8">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Code <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_weway_code') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_code">
                                                @if ($errors->has('employer_store_weway_code'))
                                                    <p class="is-error">{{ $errors->first('employer_store_weway_code') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Company <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_company') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_company">
                                                @if ($errors->has('employer_store_company'))
                                                    <p class="is-error">{{ $errors->first('employer_store_company') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            VAT Number <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_vat') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_vat">
                                                @if ($errors->has('employer_store_vat'))
                                                    <p class="is-error">{{ $errors->first('employer_store_vat') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            First Contact <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_firstContact') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_firstContact">
                                                @if ($errors->has('employer_store_firstContact'))
                                                    <p class="is-error">{{ $errors->first('employer_store_firstContact') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            FC Title
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_fcTitle') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_fcTitle">
                                                {{-- @if ($errors->has('employer_store_fcTitle'))
                                                    <p class="is-error">{{ $errors->first('employer_store_fcTitle') }}</p>
                                                @endif --}}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            FC Number Email <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_company') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_fcNumber">
                                                {{-- @if ($errors->has('employer_store_company'))
                                                    <p class="is-error">{{ $errors->first('employer_store_company') }}</p>
                                                @endif --}}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Second Contact
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_secondContact') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_secondContact">
                                                @if ($errors->has('employer_store_secondContact'))
                                                    <p class="is-error">{{ $errors->first('employer_store_secondContact') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            SC Title 
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_scTitle') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_scTitle">
                                                {{-- @if ($errors->has('employer_store_scTitle'))
                                                    <p class="is-error">{{ $errors->first('employer_store_scTitle') }}</p>
                                                @endif --}}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            SC number 
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_scNumber') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_scNumber">
                                                @if ($errors->has('employer_store_scNumber'))
                                                    <p class="is-error">{{ $errors->first('employer_store_scNumber') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Address <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_address') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_address">
                                                @if ($errors->has('employer_store_address'))
                                                    <p class="is-error">{{ $errors->first('employer_store_address') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Tax Code <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_taxCode') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_taxCode">
                                                @if ($errors->has('employer_store_taxCode'))
                                                    <p class="is-error">{{ $errors->first('employer_store_taxCode') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Website <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{old('_token') ? old('employer_store_website') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_website">
                                                @if ($errors->has('employer_store_website'))
                                                    <p class="is-error">{{ $errors->first('employer_store_website') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Payroll Payment Date <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_payrollDate') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_payrollDate">
                                                @if ($errors->has('employer_store_payrollDate'))
                                                    <p class="is-error">{{ $errors->first('employer_store_payrollDate') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Advance All Salary <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_advanceAll') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_advanceAll">
                                                @if ($errors->has('employer_store_advanceAll'))
                                                    <p class="is-error">{{ $errors->first('employer_store_advanceAll') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Advance Date Adjustment <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_advanceDate') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_advanceDate">
                                                @if ($errors->has('employer_store_advanceDate'))
                                                    <p class="is-error">{{ $errors->first('employer_store_advanceDate') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Advance Percentage <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_advance_percentage') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_advance_percentage">
                                                @if ($errors->has('employer_store_advance_percentage'))
                                                    <p class="is-error">{{ $errors->first('employer_store_advance_percentage') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Fee Tariff <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-8">
                                                <input type="number" value="{{old('_token') ? old('employer_store_fee_tariff') : ""}}" class="form-control custom-table-margin addnew" name="employer_store_fee_tariff">
                                                @if ($errors->has('employer_store_fee_tariff'))
                                                    <p class="is-error">{{ $errors->first('employer_store_fee_tariff') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Company status
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employer_store_company_status_1" type="radio" name="employer_store_company_status" @if(!old('_token') || old('employer_store_company_status') == 1) checked @endif>
                                                    Current
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employer_store_company_status_2" name="employer_store_company_status" value="2" @if(old('_token') && old('employer_store_company_status') == 2) checked @endif>
                                                    Stop
                                                </label>
                                                @if ($errors->has('employer_store_company_status'))
                                                    <p class="is-error">{{ $errors->first('employer_store_company_status') }}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Payroll status
                                        </th>
                                        <td>
                                            <div class="col-sm-10">
                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" value="1" id="employer_store_payroll_status_1" type="radio" name="employer_store_payroll_status" @if(!old('_token') || old('employer_store_payroll_status') == 1) checked @endif>
                                                    Current
                                                </label>

                                                <label class="col-sm-4 text-left">
                                                    <input class="custom-table-margin" type="radio" id="employer_store_payroll_status_2" name="employer_store_payroll_status" value="2" @if(old('_token') && old('employer_store_payroll_status') == 0) checked @endif>
                                                    Stop
                                                </label>
                                                @if ($errors->has('employer_store_payroll_status'))
                                                    <p class="is-error">{{ $errors->first('employer_store_payroll_status') }}</p>
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
        function getIdemployerEdit(id){
            $('html, body').animate({
                scrollTop: ($('#form-edit').offset().top - 60)
            }, 1000);

            var employeeId = $('input[name=confirm_change]:checked').val();
            var id = $("#update-"+employeeId);
            // get value id
            data= {
                code : id.find("td:eq(1)").html(),
                company : id.find("td:eq(2)").html(),
                vat_number : id.find("td:eq(3)").html(),
                first_contact : id.find("td:eq(4)").html(),
                fc_title : id.find("td:eq(5)").html(),
                fc_number : id.find("td:eq(6)").html(),
                second_contact : id.find("td:eq(7)").html(),
                sc_title : id.find("td:eq(8)").html(),
                sc_number : id.find("td:eq(9)").html(),
                address : id.find("td:eq(10)").html(),
                tax_code : id.find("td:eq(11)").html(),
                website : id.find("td:eq(12)").html(),
                payroll_payment_date : id.find("td:eq(13)").html(),
                advance_all_salary_date : id.find("td:eq(19)").html(),
                advance_date_adjustment : id.find("td:eq(14)").html(),
                advance_percentage : id.find("td:eq(15)").html(),
                company_status : id.find("td:eq(16)").html(),
                payroll_status : id.find("td:eq(17)").html(),
                fee_tariff : id.find("td:eq(18)").html(),
            }
            if( data.company_status == "current"){
                $("#employer_store_company_status_1").prop("checked", true);
            }
            else{
                $("#employer_store_company_status_2").prop("checked", true);
            }
            if( data.payroll_status == "current"){
                $("#employer_store_payroll_status_1").prop("checked", true);
            }
            else{
                $("#employer_store_payroll_status_2").prop("checked", true);
            }
            // fill input
            $("input[name=employer_store_id]").val(employeeId);
            $("input[name=employer_store_code]").val(data.code);
            $("input[name=employer_store_company]").val(data.company);
            $("input[name=employer_store_vat]").val(data.vat_number);
            $("input[name=employer_store_firstContact]").val(data.first_contact);
            $("input[name=employer_store_fcTitle]").val(data.fc_title);
            $("input[name=employer_store_fcNumber]").val(data.fc_number);
            $("input[name=employer_store_secondContact]").val(data.second_contact);
            $("input[name=employer_store_scTitle]").val(data.sc_title);
            $("input[name=employer_store_scNumber]").val(data.sc_number);
            $("input[name=employer_store_address]").val(data.address);
            $("input[name=employer_store_taxCode]").val(data.tax_code);
            $("input[name=employer_store_website]").val(data.website);
            $("input[name=employer_store_payrollDate]").val(data.payroll_payment_date);
            $("input[name=employer_store_advanceAll]").val(data.advance_all_salary_date);
            $("input[name=employer_store_advanceDate]").val(data.advance_date_adjustment);
            $("input[name=employer_store_fee_tariff]").val(data.fee_tariff);
            $("input[name=employer_store_advance_percentage]").val(data.advance_percentage);
        }
    </script>
@endsection
@section('css_bottom')
@endsection