$(document).ready(function () {

    console.log(dataModule);





    $('.select2-serverside').each(async function (i, v) {
        var elm = $(this).attr('id');
        var table = $(this).attr('data-table');
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-text');
        var selected = $(this).attr('data-selected');

        var initialdata = [];
        if (selected != '') {
            try {
                initialdata = await select2initialselect(table, id, text, selected);
            } catch (err) { }
        }
        if (initialdata.length > 0) {
            $('#' + elm).append('<option selected="selected" value="' + initialdata[0].id + '">' + initialdata[0].text + '</option>');
        }
        var url = base_url + 'select2?table=' + table + '&id=' + id + '&text=' + text;

        $('#' + elm).select2({
            ajax: {
                url: url,
                dataType: 'json'
            },
            minimumInputLength: 3,
        });


    });

    if (dataModule.data_filter) {
        setdefaultValue();
    } else {
        $('input[name=date-range-picker]').daterangepicker({
            // startDate: moment().subtract('days', 29),
            // endDate: moment(),
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                format: 'DD/MMM/YYYY',
            }
        })
            .prev().on(ace.click_event, function () {
                $(this).next().focus();
            });
    }



});

function setdefaultValue() {
    let d = dataModule.data_filter;
    // $('#start').val(d.start);
    // $('#end').val(d.end);

    if (parseInt(d.include_weekend) > 0) {
        $('#include_weekend').prop('checked', true);
    }
    if (parseInt(d.include_holiday) > 0) {
        $('#include_holiday').prop('checked', true);
    }

    if(d.ordering_by!=''){
        $('#ordering_by').val(d.ordering_by);
    } else {
        $('#ordering_by').val('nik');
    }
    

    $('input[name=date-range-picker]').daterangepicker({
        startDate: moment(d.start),
        endDate: moment(d.end),
        'applyClass': 'btn-sm btn-success',
        'cancelClass': 'btn-sm btn-default',

        locale: {
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
            format: 'DD/MMM/YYYY',
        }
    })
        .prev().on(ace.click_event, function () {
            $(this).next().focus();
        });




    App_template.loadingStart()

    setTimeout(() => {
        showingresult();
        App_template.loadingEnd();
    }, 3000)
}

function select2initialselect(table, id, text, selected) {
    return new Promise((resolve, reject) => {
        let url = base_url + 'select2/initialselect?table=' + table + '&id=' + id + '&text=' + text + '&selected=' + selected;
        try {
            $.getJSON(url, function (jsonResult) {
                resolve(jsonResult);
            });
        } catch (err) {
            reject('');
        }
    });
}

$('#btnshowfilter').click(function () {
    showingresult();
});

function showingresult() {

    let start = $('input[name=date-range-picker]').data('daterangepicker').startDate.format('YYYY-MM-DD');
    let end = $('input[name=date-range-picker]').data('daterangepicker').endDate.format('YYYY-MM-DD');

    // console.log(start);
    // console.log(end);
    // return false;

    // let start = $('#start').val();
    // let end = $('#end').val();

    let employee_list = $('#employee_id').select2("val");
    let employee_select2 = $('#employee_id').select2('data');

    let include_weekend = ($('#include_weekend').is(':checked')) ? 1 : 0;
    let include_holiday = ($('#include_holiday').is(':checked')) ? 1 : 0;
    let ordering_by = $('#ordering_by').val();

    if (start != '' && start != null && start != 'null' &&
        end != '' && end != null && end != 'null' &&
        employee_list != '' && employee_list != null && employee_list != 'null') {
        let url = dataModule.module_url + 'getreport';
        $.post(url, {
            start: start,
            end: end,
            employee_list: JSON.stringify(employee_list),
            employee_select2: JSON.stringify(employee_select2),
            include_weekend: include_weekend,
            include_holiday: include_holiday,
            ordering_by: ordering_by
        }, function (jsonResult) {

            $('#accordion').html('');

            let progressStart = 100 / jsonResult.length;
            let progress = 0;

            $('#view_result').html('Result : <b class="blue">' + jsonResult.length + '</b> employees <a href="' + dataModule.module_url + 'printreport" target="_blank" class="btn btn-xs btn-warning pull-right">Print Report</a>');
            $('#view_result').append('<hr/>');

            console.log(jsonResult);
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
        });
    } else {
        toastr.warning('Please fill the required form');
    }


}

$(document).on('click', '.btn-print-personal', function () {
    let fingerprint_id = $(this).attr('data-id');
    let url = dataModule.module_url + 'printreport?s=y&fingerprint_id=' + fingerprint_id;
    window.open(url, 'Report Attendance');
})