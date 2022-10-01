$(document).ready(function () {
    console.log(dataModule);
    if (dataModule.data_filter) {
        showfilter();
    }
});

function showfilter() {

    // console.log();
    App_template.loadingStartWithProgress();


    let url = dataModule.module_url + 'getreport?s=y';
    $.getJSON(url, function (jsonResult) {
        // console.log(jsonResult);



        let progressStart = 100 / jsonResult.length;
        let progress = 0;

        // console.log();

        let list_month = '';
        let months = moment.months();
        let month_in_filter = moment(dataModule.data_filter.data.end_date).format('MM');

        for (let i = 0; i < months.length; i++) {
            var selected = (parseInt(month_in_filter) == (i + 1)) ? 'selected' : '';
            list_month = list_month + '<option value="' + (i + 1) + '" ' + selected + '>' + months[i] + '</option>';
        };


        // console.log('month_in_filter', parseInt(month_in_filter));

        let year_in_filter = moment(dataModule.data_filter.data.end_date).format('YYYY');
        let year_1_min = moment(dataModule.data_filter.data.end_date).subtract(1, 'y').format('YYYY');
        let year_2_min = moment(dataModule.data_filter.data.end_date).subtract(2, 'y').format('YYYY');
        let year_1 = moment(dataModule.data_filter.data.end_date).add(1, 'y').format('YYYY');
        let year_2 = moment(dataModule.data_filter.data.end_date).add(2, 'y').format('YYYY');
        // console.log('year_in_filter', year_in_filter);


        $('#view_result').html('Result : <b class="blue">' + jsonResult.length + '</b> employees');

        if (jsonResult.length > 0) {
            $('#view_submit_to_finance').html(`<div class="well">
                <div class="row">
                    <div class="col-md-2">
                        <b>Submit this report to payroll : </b>
                    </div>
                    <div class="col-md-2">
                        <select id="report_month" class="form-control">
                            ${list_month}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="report_year" class="form-control">
                            <option value="${year_1_min}">${year_1_min}</option>
                            <option value="${year_2_min}">${year_2_min}</option>
                            <option value="${year_in_filter}" selected>${year_in_filter}</option>
                            <option value="${year_1}">${year_1}</option>
                            <option value="${year_2}">${year_2}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button id="report_submit" class="btn btn-xs btn-success">Submit</button>
                        <div class="pull-right">
                            <a href="${dataModule.module_url}printreport?s=y" target="_blank" class="btn btn-xs btn-warning">Print Report</a>
                        </div>
                    </div>
                </div>
            </div>`);
        }

        // console.log(jsonResult);
        if (jsonResult.length > 0) {
            $.each(jsonResult, function (i, v) {

                var tr = '';

                var working_days = 0;
                var total_working_hours = 0;
                var first_date = '';
                var last_date = '';

                // moment().format('DD MMM YYYY')

                $.each(v.list_date, function (i2, v2) {

                    first_date = (i2 == 0) ? v2.date : first_date;
                    last_date = (i2 == (v.list_date.length - 1)) ? v2.date : last_date;

                    var attd_in = (v2.attendance.length > 0) ?
                        moment(v2.attendance[0]['in_date']).format('DD MMM YYYY') + ' ' + v2.attendance[0]['in_time'].substring(0, 5) : '';
                    var attd_out = (v2.attendance.length > 0) ?
                        moment(v2.attendance[0]['out_date']).format('DD MMM YYYY') + ' ' + v2.attendance[0]['out_time'].substring(0, 5) : '';
                    var attd_wh = (v2.attendance.length > 0) ? v2.attendance[0]['working_hours'] : '';
                    var attd_desc = (v2.attendance.length > 0) ?
                        (v2.attendance[0]['attendance_description_name'] != '' && v2.attendance[0]['attendance_description_name'] != null && v2.attendance[0]['attendance_description_name'] != 'null') ?
                            v2.attendance[0]['attendance_description_name'] : '' : '';
                    var attd_ot_desc = (v2.attendance.length > 0) ?
                        (v2.attendance[0]['other_descriptions'] != '' && v2.attendance[0]['other_descriptions'] != null && v2.attendance[0]['other_descriptions'] != 'null') ?
                            v2.attendance[0]['other_descriptions'] : '' : '';

                    var l_category = (v2.leave.length > 0) ? v2.leave[0]['leave_category_name'] : '';
                    var l_reason = (v2.leave.length > 0) ? v2.leave[0]['reason'] : '';
                    var l_type = (v2.leave.length > 0)
                        ? (v2.leave[0]['type'] == '2')
                            ? 'Potong Gaji' : (v2.leave[0]['type'] == '1') ? 'Potong Cuti' : 'Tidak Potong Cuti / Gaji'
                        : '';

                    var red_class = (attd_wh == '') ? 'class="red"' : '';

                    var class_wh = 'danger';
                    if (attd_wh != '') {
                        class_wh = (parseFloat(attd_wh) >= 8) ? 'success' : 'warning';
                        working_days = working_days + 1;
                        total_working_hours = total_working_hours + parseFloat(attd_wh);
                    }

                    var new_tr = `<tr>
                        <td>${i2 + 1}</td>
                        <td ${red_class}>${moment(v2.date).format('DD MMM YYYY')}</td>
                        <td>${v2.date_name}</td>
                        <td>${attd_in}</td>
                        <td>${attd_out}</td>
                        <td class="${class_wh}">${attd_wh}</td>
                        
                        <td>${l_category}</td>
                        <td>${l_type}</td>
                        <td>${l_reason}</td>
                    </tr>`;
                    tr = tr + new_tr;
                })

                let is_in = (i == 0) ? '' : '';

                var new_elm = `
                

                <div class="panel panel-default">

                    <div class="panel-heading">
                    
                        <h4 class="panel-title">
                        <button data-id="${v.fingerprint_id}" class="btn btn-white btn-warning pull-right btn-print-personal" style="z-index:1;">Print Report</button>
                        <button data-id="${v.fingerprint_id}" data-start="${first_date}" data-end="${last_date}" class="hide btn btn-white btn-warning pull-right btn-resync-attendance" style="z-index:1;">Print Report</button>
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_${i}">
                                <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                ${v.nik} - ${v.name}
                            </a>
                            
                        </h4>
                    </div>


                    <div id="collapse_${i}" class="panel-collapse collapse ${is_in}">
                        <div class="panel-body">
                            <div class="row">
                                    <div class="col-md-4">
                                        <table class="table table-striped">
                                            <tr>
                                                <td style="width:10%;">NIK</td>
                                                <td style="width:3%;">:</td>
                                                <td>${v.nik}</td>
                                            </tr>
                                            <tr>
                                                <td>Name</td>
                                                <td>:</td>
                                                <td>${v.name}</td>
                                            </tr>
                                            <tr>
                                                <td>PT</td>
                                                <td>:</td>
                                                <td>${v.pt_name}</td>
                                            </tr>
                                            <tr>
                                                <td>Department</td>
                                                <td>:</td>
                                                <td>${v.department_name}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;" class="blue" colspan="3">Generate Date - ${moment().format('DD MMM YYYY HH:mm')}</td>
                                            </tr>
                                            
                                            
                                        </table>
                                    </div>
                                    <div class="col-md-4 col-md-offset-4">
                                        <table class="table table-bordered table-striped table-report-resume">
                                            <tr>
                                                <th style="text-align:center;" colspan="3">Periode : ${moment(first_date).format('DD MMM YYYY')} - ${moment(last_date).format('DD MMM YYYY')}</th>
                                            </tr>
                                            <tr>
                                                <td>Total Days</td>
                                                <td style="width:3%;">:</td>
                                                <td style="width:20%;">${v.list_date.length}</td>
                                            </tr>
                                            <tr>
                                                <td>Working Days</td>
                                                <td>:</td>
                                                <td>${working_days}</td>
                                            </tr>
                                            <tr>
                                                <td>Off Days</td>
                                                <td>:</td>
                                                <td>${parseFloat(v.list_date.length) - working_days}</td>
                                            </tr>
                                            <tr>
                                                <td>Number of Working Hours</td>
                                                <td>:</td>
                                                <td>${Math.round(total_working_hours, 2)}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-report">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="width: 3%;">No</th>
                                                    <th rowspan="2" style="width: 10%;">Date</th>
                                                    <th rowspan="2" style="width: 10%;">Name</th>
                                                    <th rowspan="2" style="width: 13%;">In</th>
                                                    <th rowspan="2" style="width: 13%;">Out</th>
                                                    <th rowspan="2" style="width: 5%;">Work Hour</th>
                                                    
                                                    <th colspan="3">Permissions</th>
                                                </tr>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Type</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>${tr}</tbody>
                                        </table>
                                    </div>
                                </div>



                        </div>
                    </div>
                </div>

                
                `;

                $('#accordion').append(new_elm);

                progress = progress + progressStart;
                $('#dataLoadingStartWithProgress').html(Math.round(progress));
            });
        } else {
            $('#accordion').html('');
        }

        App_template.loadingEnd(0);
    });
}



$(document).on('click', '#report_submit', function () {
    var month = $('#report_month').val();
    var year = $('#report_year').val();
    let filter = dataModule.data_filter.data;

    if (month != '' && month != null && month != 'null' &&
        year != '' && year != null && year != 'null') {

        if (confirm('Are you sure?')) {
            let url = dataModule.module_url + 'savereportattendance';
            $.post(url, { month: month, year: year, filter: filter }, function (result) {
                toastr.success('Success!');
            });
        }

    }
});

$(document).on('click', '.btn-resync-attendance', function () {
    let id = $(this).attr('data-id');
    let date_start = $(this).attr('data-start');
    let date_end = $(this).attr('data-end');

    console.log(id);
    console.log(date_start);
    console.log(date_end);

    if (id != '' && id != null && id != 'null' &&
        date_start != '' && date_start != null && date_start != 'null' &&
        date_end != '' && date_end != null && date_end != 'null') {
        // console.log(dataModule);
        let url = dataModule.module_url + 'resyncAttendance';
        $.post(url, {
            id: id,
            date_start: date_start,
            date_end: date_end
        }, function (result) {

        });
    }

});

$(document).on('click', '.btn-print-personal', function () {
    let fingerprint_id = $(this).attr('data-id');
    let url = dataModule.module_url + 'printreport?s=y&fingerprint_id=' + fingerprint_id;
    window.open(url, 'Report Attendance');
})
