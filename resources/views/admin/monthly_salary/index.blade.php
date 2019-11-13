@extends('admin.layout.app')

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
                    <div class="box-header ">
                        <h1 class="">Employee Payroll</h1>
                    </div>
                    <div class="box custom-box box-info" style="margin-bottom: 10px;">
                        <form class="form-horizontal" method="POST" action="{{ route('employee-payroll.index', isset($employeeData['employee_id']) ? $employeeData['employee_id'] : 0) }}" id="search_form">
                            @csrf
                            <div class="box-body">
                                <table class="col-sm-12">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header col-sm-4">
                                            Month
                                        </th>
                                        <td>
                                            <div class="col-sm-2">
                                                <input type="text" readonly data-toggle="datepicker" autocomplete="off" class="form-control custom-table-margin" name="year_month" id="year_month" value="{{$employeeData['searchValue']['year_month'] != null ? $employeeData['searchValue']['year_month'] : old('year_month')}}">
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

                    <div id="form-edit">
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
                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Monthly Salary Table</h3>
                        </div>
                        <div class="box-body">
                            @if(empty($employeeData['employee_payroll']))
                                <p>No data</p>
                            @else
                            <form class="form-horizontal" id="form-update" method="POST" action="{{ route('employee-payroll.store', isset($employeeData['employee_id']) ? $employeeData['employee_id'] : 0) }}">
                                @csrf
                                <table class="col-md-12">
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create  col-sm-4">
                                            Weway customer number
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                <p>{{ $employeeData['employee_payroll']->weway_customer_number }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Employee Number
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                <p>{{ $employeeData['employee_payroll']->employee_number ? $employeeData['employee_payroll']->employee_number : '--'}}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Name
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                <p>{{ $employeeData['employee_payroll']->surname . ' ' . $employeeData['employee_payroll']->middle_name . ' ' . $employeeData['employee_payroll']->name  }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Month
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                <p>{{$employeeData['searchValue']['year_month'] != null ? $employeeData['searchValue']['year_month'] : old('year_month')}}</p>
                                                <input type="hidden" id="year_month" value="{{$employeeData['searchValue']['year_month'] != null ? $employeeData['searchValue']['year_month'] : old('year_month')}}" class="form-control custom-table-margin" name="year_month">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Contractual Salary <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                @if($employeeData['enableToUpdate'])
                                                    <input type="text" id="contractual_salary" value="{{old('_token') ? old('contractual_salary') : $employeeData['employee_payroll']->contractual_salary}}" class="form-control custom-table-margin" name="contractual_salary">
                                                    @if ($errors->has('contractual_salary'))
                                                        <p class="is-error">{{ $errors->first('contractual_salary') }}</p>
                                                    @endif
                                                @else
                                                    <p>{{old('_token') ? old('contractual_salary') : $employeeData['employee_payroll']->contractual_salary}}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Net Salary <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                @if($employeeData['enableToUpdate'])
                                                    <input type="text" id="net_salary" onkeyup="calAmount()" value="{{old('_token') ? old('net_salary') : $employeeData['employee_payroll']->net_salary}}" class="form-control custom-table-margin" name="net_salary">
                                                    @if ($errors->has('net_salary'))
                                                        <p class="is-error">{{ $errors->first('net_salary') }}</p>
                                                    @endif
                                                @else
                                                    <p>{{old('_token') ? old('net_salary') : $employeeData['employee_payroll']->net_salary}}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Standard working days <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                @if($employeeData['enableToUpdate'])
                                                    <input type="text" id="standard_working_days" onkeyup="calAmount()" value="{{old('_token') ? old('standard_working_days') : $employeeData['employee_payroll']->standard_working_days}}" class="form-control custom-table-margin" name="standard_working_days">
                                                    @if ($errors->has('standard_working_days'))
                                                        <p class="is-error">{{ $errors->first('standard_working_days') }}</p>
                                                    @endif
                                                @else
                                                    <p>{{old('_token') ? old('standard_working_days') : $employeeData['employee_payroll']->standard_working_days}}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Working day adjustment <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                @if($employeeData['enableToUpdate'])
                                                    <input type="text" id="working_day_adjustment" value="{{old('_token') ? old('working_day_adjustment') : $employeeData['employee_payroll']->working_day_adjustment}}" class="form-control custom-table-margin" name="working_day_adjustment">
                                                    @if ($errors->has('working_day_adjustment'))
                                                        <p class="is-error">{{ $errors->first('working_day_adjustment') }}</p>
                                                    @endif
                                                @else
                                                    <p>{{old('_token') ? old('working_day_adjustment') : $employeeData['employee_payroll']->working_day_adjustment}}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Actual working days <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                @if($employeeData['enableToUpdate'])
                                                    <input type="text" id="actual_working_days" onkeyup="calAmount()" value="{{old('_token') ? old('actual_working_days') : $employeeData['employee_payroll']->actual_working_days}}" class="form-control custom-table-margin" name="actual_working_days">
                                                    @if ($errors->has('actual_working_days'))
                                                        <p class="is-error">{{ $errors->first('actual_working_days') }}</p>
                                                    @endif
                                                @else
                                                    <p>{{old('_token') ? old('actual_working_days') : $employeeData['employee_payroll']->actual_working_days}}</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Max drawable salary <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                <p id="max_drawable_salary">{{old('_token') ? number_format(old('net_salary') / old('standard_working_days') * old('actual_working_days'), 0, '', '') :
                                                   number_format($employeeData['employee_payroll']->net_salary / $employeeData['employee_payroll']->standard_working_days * $employeeData['employee_payroll']->actual_working_days, 0, '', '')}}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create">
                                            Daily drawable amount <span class="color-red">*</span>
                                        </th>
                                        <td>
                                            <div class="col-sm-12">
                                                <p id="daily_drawble_amount">{{old('_token') ? number_format(old('net_salary') / old('standard_working_days'), 0, '', '') :
                                                   number_format($employeeData['employee_payroll']->net_salary / $employeeData['employee_payroll']->standard_working_days, 0, '', '')}}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="custom-table-border">
                                        <th class="custom-table-header-create"></th>
                                        <td>
                                            <div class="col-sm-12">
                                                @if($employeeData['enableToUpdate'])

                                                <button type="submit" id="btn-submit-form" class="btn-custom btn-default pull-left margin-5px">Save</button>
                                                @else
                                                    <p class="red">* You can only update before the 5th of the month.</p>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            @endif
                        </div>
                    </div>

                    <div class="custom-table-box margin-top-20px">

                        <div class="box-header custom-table-box-header">
                            <h3 class="box-title">Monthly Transaction Table</h3>
                        </div>
                        @if(isset($employeeData["payroll"]))
                            <div class="box-body">
                                <table id="sortable" class="col-md-12 table table-bordered table-striped table-hover" style="min-width: 1400px">
                                    <thead>
                                    <tr style="background-color: #D3D3D3;">
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Working Day</th>
                                        <th>Cumulative Day</th>
                                        <th>Drawable Amount</th>
                                        <th class="col-sm-2">Description</th>
                                        <th>Advanced</th>
                                        <th>Total Advance</th>
                                        <th class="col-sm-1">Payment</th>
                                        <th>Total Payment</th>
                                        <th>Net Amount</th>
                                        <th>Remaining Drawable</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($employeeData["payroll"]))
                                        @foreach ($employeeData["payroll"] as $key => $payroll)
                                            <tr class="user" onclick="clickCheckbox({{$key}})">
                                                <td>{{ $payroll['date']}}</td>
                                                <td>{{ $payroll['day']}}</td>
                                                <td>{{ $payroll['working_day']}}</td>
                                                <td>{{ $payroll['cumulative_day']}}</td>
                                                <td>{{ number_format($payroll['drawable_amount'], 0, '', '')}}</td>
                                                <td style="text-align: left">
                                                    @if($payroll['enable_edit'])
                                                        <input type="text" id="description-{{ $payroll['date']}}" value="{{ $payroll['description']}}" class="description col-sm-12" name="description">
                                                    @else
                                                        {{ $payroll['description']}}
                                                    @endif
                                                </td>
                                                <td>{{ $payroll['advanced']}}</td>
                                                <td>{{ $payroll['total_advance']}}</td>
                                                <td class="">
                                                    @if($payroll['enable_edit'])
                                                        <input type="text" id="payment-{{ $payroll['date']}}" onkeyup="calAmount2()" value="{{ $payroll['payment']}}" class="col-sm-12 payment" name="payment">
                                                    @else
                                                        {{ $payroll['payment'] == 0 ? '-' : $payroll['payment']}}
                                                    @endif
                                                </td>
                                                <td>{{ $payroll['total_payment']}}</td>
                                                <td>{{ $payroll['net_amount']}}</td>
                                                <td>{{ number_format($payroll['remaining_drawable'], 0, '', '')}}</td>
                                                <td>
                                                    @if($payroll['enable_edit'])
                                                        <button class="btn-custom btn-default " onclick="updatePayment('{{ $payroll['date']}}','{{$employeeData["year_month"]}}' )">Update</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('javascript_bottom')
    <script>
        function refreshPage () {
            var page_y = document.getElementsByTagName("body")[0].scrollTop;
            window.location.href = window.location.href.split('?')[0] + '?page_y=' + page_y;
        }
        window.onload = function () {
            // setTimeout(refreshPage, 35000);
            if ( window.location.href.indexOf('page_y') != -1 ) {
                var match = window.location.href.split('?')[1].split("&")[0].split("=");
                document.getElementsByTagName("body")[0].scrollTop = match[1];
            }
        }

        function updatePayment(date, yearMonth) {
            var url = '{{ route("employee-payroll.update", isset($employeeData['employee_id']) ? $employeeData['employee_id'] : 0) }}';
            var description = $('#description-' + date).val();
            var payment = $('#payment-' + date).val();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: '{{ csrf_token() }}',
                    description: description,
                    payment: payment,
                    date: date,
                    year_month: yearMonth
                },
                dataType: 'json',
            }).done(function (data) {
                location.reload();
            });

        }

        // Restricts input for the given textbox to the given inputFilter.
        function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    }
                });
            });
        }

        function calAmount(e) {
            var net_salary = $('#net_salary').val();
            var standard_working_days = $('#standard_working_days').val();
            var actual_working_days = $('#actual_working_days').val();
            if (standard_working_days > 0) {
                var daily_drawble_amount = net_salary / standard_working_days;
                var max_drawable_salary = daily_drawble_amount * actual_working_days;
                $('#daily_drawble_amount').text(daily_drawble_amount.toFixed(0));
                $('#max_drawable_salary').text(max_drawable_salary.toFixed(0));
            }
        }

        function calAmount2(e) {
            var net_salary = $('#net_salary').val();
            var standard_working_days = $('#standard_working_days').val();
            var actual_working_days = $('#actual_working_days').val();
            if (standard_working_days > 0) {
                var daily_drawble_amount = net_salary / standard_working_days;
                var max_drawable_salary = daily_drawble_amount * actual_working_days;
                $('#daily_drawble_amount').text(daily_drawble_amount.toFixed(0));
                $('#max_drawable_salary').text(max_drawable_salary.toFixed(0));
            }
        }

        <?php if(!empty($employeeData['employee_payroll']) && $employeeData['enableToUpdate']) { ?>
        setInputFilter(document.getElementById("contractual_salary"), function(value) {
            return /^\d*$/.test(value); });
        setInputFilter(document.getElementById("net_salary"), function(value) {
            return /^\d*$/.test(value); });
        setInputFilter(document.getElementById("standard_working_days"), function(value) {
            return /^\d*$/.test(value) && (value === "" || (parseInt(value) <= 31 && parseInt(value) > 0)); });
        setInputFilter(document.getElementById("working_day_adjustment"), function(value) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 31); });
        setInputFilter(document.getElementById("actual_working_days"), function(value) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 31); });
        <?php } ?>

        <?php if(!empty($employeeData["payroll"])) {
            foreach ($employeeData["payroll"] as $key => $payroll) {
                if(!$payroll['enable_edit']) {continue;}?>
            setInputFilter(document.getElementById("payment-{{$payroll['date']}}"), function(value) {
                return /^\d*$/.test(value); });
        <?php } }?>

        $(document).ready(function() {
            window.objectUserDataOld = JSON.parse('<?php echo json_encode(isset($employeeData['searchValue']) ? $employeeData['searchValue'] : []); ?>');
            $.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );

            $('#year_month').datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'mm-yy',
                monthNames: ["1","2","3","4","5","6","7","8","9","10","11","12"],
                monthNamesShort: ["1","2","3","4","5","6","7","8","9","10","11","12"],
                maxDate: new Date(),
                minDate: new Date(2018, 12, 31),
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).val($.datepicker.formatDate('mm-yy', new Date(year, month, 1)));
                },
                beforeShow : function(input, inst) {
                    var tmp = $(this).val().split('-');
                    $(this).datepicker('option','defaultDate',new Date(tmp[1],tmp[0]-1,1));
                    $(this).datepicker('setDate', new Date(tmp[1], tmp[0]-1, 1));
                }
            });
        });
    </script>
@endsection
@section('css_bottom')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
        #form-update table tr td div.col-sm-12 p {
            text-align: left;
            margin: 5px;
            padding-left: 10px;
        }
        .col-sm-2 {
            min-width: 100px;
        }
        #year_month {
            text-align: center;
        }
        .red {
            color: Red;
        }
        #sortable .col-sm-12 {
            text-align: center;
        }
        #sortable input:hover, #sortable tr:hover input, #sortable tr:hover button  {
            color: #000000;
        }
        #sortable .description {
            text-align: left ;
        }
    </style>
@endsection
