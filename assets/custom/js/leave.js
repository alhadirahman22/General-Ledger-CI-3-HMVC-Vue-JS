$(document).ready(function () {
    console.log(dataModule);
    setTimeout(() => {
        getDetailEmp('edit');
    }, 1500);

    $('#reason').parent().append('<div id="alert_reason"></div>');
    // $('#reason').prop('', '77');

});

$(document).on('keyup', '#reason', function () {
    let reason = $('#reason').val();
    $('#alert_reason').html('Maximum 77 character ( ' + reason.length + ' - 77 )');
});

$('#start_date').change(function () {
    countDiffDay();
});

$('#end_date').change(function () {
    countDiffDay();
});

async function countDiffDay() {

    let employee_id = $('#employee_id').val();

    if (employee_id != 'null' && employee_id != null &&
        typeof employee_id != 'undefined' && employee_id != '' && employee_id != 0) {

        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();

        if (start_date != '' && start_date != null && start_date != 'null' &&
            end_date != '' && end_date != null && end_date != 'null') {
            var a = moment(start_date);
            var b = moment(end_date);

            let working_day = parseFloat(b.diff(a, 'days')) + 1;
            if (working_day > 0) {

                let exclude = 0;
                let view_exclude = '';

                let data_cuti_type = $('#data_cuti_type').val();
                if (data_cuti_type == 'Tahunan') {
                    // cek tanggal
                    for (let i = 0; i < working_day; i++) {
                        // const element = array[i];
                        let date = moment(start_date).add(i, 'days').format('YYYY-MM-DD');
                        // console.log(date)
                        let result = await is_holiday(date);
                        if (result.status) {
                            exclude = exclude + 1;
                            let _view_exclude = `<li>${result.date} | ${result.message}</li>`;
                            view_exclude = view_exclude + _view_exclude;
                        }
                        // console.log(date, result);
                    }
                }

                let data_working_day = ((working_day - exclude) > 0) ? working_day - exclude : 0;
                $('#working_day').val(data_working_day);

                if (exclude > 0) {
                    $('#view_exclude').remove();
                    $('#working_day').parent().parent().append(`<div id="view_exclude" class="col-md-7"><ul>${view_exclude}</ul></div>`);
                }


            } else {
                $('#working_day').val('');
                alert('End date must be greater than start date');
            }
            // console.log();

        }

    } else {
        alert('Please select employee first');
        $('#start_date').val('');
        $('#end_date').val('');
    }

}

function is_holiday(date) {
    return new Promise((resolve, reject) => {

        let url = dataModule.module_url + 'isholidaydate?date=' + date;
        $.getJSON(url, function (jsonResult) {
            resolve(jsonResult);
        });

    });
}

$('#substitution_by').change(function () {

    let substitution_by = $('#substitution_by').val();
    let employee_id = $('#employee_id').val();

    if (substitution_by != '' && substitution_by != null && substitution_by != 'null' && typeof substitution_by !== 'undefined') {

        if (employee_id != '' && employee_id != null && employee_id != 'null' && typeof employee_id !== 'undefined') {

            if (employee_id != substitution_by) {

            } else {
                alert('Please, select other employee');
                $("#substitution_by").val('').trigger('change');
            }

        } else {
            alert('Please, select the employee first');
            $("#substitution_by").val('').trigger('change');
        }
    }

});

$('#employee_id').change(function () {
    getDetailEmp('add');
});

$('#type').change(function () {

    let working_day = $('#working_day').val();
    let data_paid_leave = $('#data_paid_leave').val();
    let type = $('#type').val();

    if (working_day != '') {
        if (type == '1') {

            if (parseInt(data_paid_leave) < parseInt(working_day)) {
                alert('Lama cuti melebihi jatah cuti karyawan');
                $('#type').val('');
            }
        }
    } else {
        alert('Please, select date first');
    }





});

function getDetailEmp(action) {
    let employee_id = $('#employee_id').val();
    if (employee_id != 0 && employee_id != null &&
        typeof employee_id != 'undefined' &&
        employee_id != '') {

        if (action != 'edit') {
            $('#working_day').val('');
            $('#start_date').val('');
            $('#end_date').val('');
        }

        $('#view_exclude').remove();

        let url = dataModule.module_url + 'getDetailEmployee?employee_id=' + employee_id;
        $.getJSON(url, function (jsonResult) {
            if (jsonResult.length > 0) {
                let d = jsonResult[0];

                let view_cuti_panel = `Leave
                <span style="font-size:10px;color:#9d9898;"><i>(Sisa Cuti)</i></span> : ${d.cuti} <input class="hide" id="data_paid_leave" value="${d.cuti}"/>
                <div>Leave Type <span style="font-size:10px;color:#9d9898;"><i>(Tipe Cuti)</i></span> : ${d.cuti_type} <input class="hide" id="data_cuti_type" value="${d.cuti_type}"/></div>
                `;

                if ($('#view_paid_leave').length == 0) {
                    $('#employee_id').parent().append(`<div style="background: #f1f1f1;padding: 10px;" id="view_paid_leave">${view_cuti_panel}</div>`);
                } else {
                    $('#view_paid_leave').html(view_cuti_panel);
                }

            } else {
                $('#view_paid_leave').html('');
            }
        });

    }
}
