$(document).ready(function () {
    console.log(dataModule);
    showfilter();
});


function showfilter() {

    // console.log();

    let url = dataModule.module_url_2 + 'getreport?s=n&id=' + dataModule.id;
    $.getJSON(url, function (jsonResult) {
        // console.log(jsonResult);

        // console.log();

        let list_month = '';
        let months = moment.months();
        let month_in_filter = moment(dataModule.data_filter.data.end_date).format('MM');

        for (let i = 0; i < months.length; i++) {
            var selected = (parseInt(month_in_filter) == (i + 1)) ? 'selected' : '';
            list_month = list_month + '<option value="' + (i + 1) + '" ' + selected + '>' + months[i] + '</option>';
        };


        $('#view_result').html('Result : <b class="blue">' + jsonResult.length + '</b> employees | <a href="' + dataModule.module_url_2 + 'printreport?s=n&id=' + dataModule.id + '" target="_blank" class="btn btn-xs btn-warning">Print Report</a>');


        if (jsonResult.length > 0) {
            $.each(jsonResult, function (i, v) {

                var tr = '';

                var working_days = 0;
                var total_working_hours = 0;
                var first_date = '';
                var last_date = '';

                $.each(v.list_date, function (i2, v2) {

                    first_date = (i2 == 0) ? moment(v2.date).format('DD MMM YYYY') : first_date;
                    last_date = (i2 == (v.list_date.length - 1)) ? moment(v2.date).format('DD MMM YYYY') : last_date;

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
                    var l_type = (v2.leave.length > 0) ? (v2.leave[0]['type']=='2') ? 'Unpaid Leave' : 'Paid Leave' : '';

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
                        <button data-id="${v.fingerprint_id}" data-start="${first_date}" data-end="${last_date}" class="hide btn btn-white btn-warning pull-right btn-resync-attendance" style="z-index:1;">Resync Attendance</button>
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
                                                <th style="text-align:center;" colspan="3">Periode : ${first_date} - ${last_date}</th>
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

                </div>`;

                $('#accordion').append(new_elm);
            });
        } else {
            $('#accordion').html('Tidak ada data');
        }
    });
}