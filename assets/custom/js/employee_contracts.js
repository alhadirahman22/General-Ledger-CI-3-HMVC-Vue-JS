$(document).ready(function () {
    console.log(dataModule);
});

$('#pt_id').change(function () {
    getAreas();
});
$('#pt_sub_id').change(function () {
    getAreas();
});

function getAreas() {
    let pt_id = $('#pt_id').val();
    let pt_sub_id = $('#pt_sub_id').val();
    if (
        pt_id != '' && pt_id != null && pt_id != 'null' && typeof pt_id !== 'undefined' &&
        pt_sub_id != '' && pt_sub_id != null && pt_sub_id != 'null' && typeof pt_sub_id !== 'undefined'
    ) {
        $('#pt_sub_area_id').empty();
        let url = dataModule.module_url + 'getAreas?pt_id=' + pt_id + '&pt_sub_id=' + pt_sub_id;
        $.getJSON(url, function (jsonResult) {
            console.log(jsonResult);
            $.each(jsonResult, function (i, v) {
                $('#pt_sub_area_id').append('<option value"' + v.pt_sub_area_id + '">' + v.name + '</option>');
            });

            if (jsonResult.length > 0) {
                $('#pt_sub_area_id').prop('disabled', false);
            } else {
                $('#pt_sub_area_id').prop('disabled', true);
            }


        });
    }
}

// =========================================

$('#employee_id').change(function () {
    let employee_id = $('#employee_id').val();
    if (employee_id != '' && employee_id != null && employee_id != 'null') {
        let url = dataModule.module_url + 'getDataPosition?employee_id=' + employee_id;
        $.getJSON(url, function (jsonResult) {
            console.log(jsonResult);
            if (jsonResult.length > 0) {
                var tr = '';
                $.each(jsonResult, function (i, v) {

                    var pt = (v.pt_name != '' && v.pt_name != null && v.pt_name != 'null')
                        ? '<div>PT : <b>' + v.pt_name + '</b></div>' : '';
                    var pt_sub_name = (v.pt_sub_name != '' && v.pt_sub_name != null && v.pt_sub_name != 'null')
                        ? '<div>Sub : <b>' + v.pt_sub_name + '</b></div>' : '';
                    var pt_sub_area_name = (v.pt_sub_area_name != '' && v.pt_sub_area_name != null && v.pt_sub_area_name != 'null')
                        ? '<div>Area : <b>' + v.pt_sub_area_name + '</b></div>' : '';

                    var is_main = (parseInt(v.is_main) > 0) ? '<span class="blue">Main Position</span>' : '<span class="orange">Other Position</span>';

                    var department_name = (v.department_name != '' && v.department_name != null && v.department_name != 'null')
                        ? '<div>Department : <b>' + v.department_name + '</b></div>' : '';

                    var position_name = (v.position_name != '' && v.position_name != null && v.position_name != 'null')
                        ? '<div>Position : <b>' + v.position_name + '</b></div>' : '';

                    var new_tr = `<tr>
                    <td style="text-align:center;">${(i + 1)}</td>
                    <td>${pt}${pt_sub_name}${pt_sub_area_name}
                    <div>
                        <button type="button" class="btn btn-white btn-purple btn-sm btn-show-migration" data-id="${v.employee_position_id}">Migration History</button>
                    </div>
                    </td>
                    <td>${department_name}${position_name}</td>
                    <td>${v.job_description_name}</td>
                    <td>${is_main}</td>
                    <td style="text-align:center;">
                        <div class="checkbox">
                            <label>
                                <input style="margin-left:-15px;" class="form-position" value="${v.employee_position_id}" type="checkbox">
                            </label>
                        </div>
                    </td>
                    </tr>`;

                    tr = tr + new_tr;
                });

                $('#panel_view_position').html(`<table class="table table-bordered table-striped">
                <tr>
                    <th style="width:3%;">No</th>
                    <th>PT</th>
                    <th style="width:23%;">Department</th>
                    <th style="width:18%;">Job Description</th>
                    <th style="width:10%;">Type</th>
                    <th style="width:5%;"></th>
                </tr>${tr}</table>`);
            } else {
                $('#panel_view_position').html('Select employee');
            }
        })
    }
});

$(document).on('change', '.form-position', function () {
    $('.form-position').prop('checked', false);
    let position_id = $(this).val();
    $('.form-position[value="' + position_id + '"]').prop('checked', true);

    $('#employee_position_id').val(position_id);
});

$(document).on('click', '.btn-show-migration', function () {
    let employee_position_id = $(this).attr('data-id');
    // console.log('employee_position_id', employee_position_id);
    let url = dataModule.module_url + 'getHistoryMigration?employee_position_id=' + employee_position_id;
    $.getJSON(url, function (jsonResult) {

        $('#ModalForm .modal-header').removeClass('hide');
        $('#ModalForm .modal-header .modal-title').html('Migration History');
        $('#ModalForm .modal-footer').removeClass('hide');
        $('#ModalForm .modal-dialog').removeClass('modal-sm modal-lg');
        $('#ModalForm .modal-dialog').addClass('modal-lg');

        $('#ModalForm .modal-footer .btn-primary').addClass('hide');

        let tr = '<tr><td colspan="8" style="text-align:center;">- - No data - -</td></tr>';
        if (jsonResult.length > 0) {
            tr = '';
            $.each(jsonResult, function (i, v) {
                // console.log(v);
                var type = (parseInt(v.is_main) == 1)
                    ? '<span class="label label-sm label-success">Main Position</span>'
                    : '<span class="label label-sm label-warning">Other Position</span>';
                var new_tr = `<tr>
                <td>${i + 1}</td>
                <td>${type}</td>
                <td>${v.pt_name}</td>
                <td>${v.pt_sub_name}</td>
                <td>${v.pt_sub_area_name}</td>
                <td>${v.department_name}</td>
                <td>${v.position_name}</td>
                <td>${v.job_description_name}</td>
                </tr>`;

                tr = tr + new_tr;
            })
        }

        $('#ModalForm .modal-body').html(`<table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Type</th>
                <th>PT</th>
                <th>Sub PT</th>
                <th>Area</th>
                <th>Department</th>
                <th>Position</th>
                <th>Job Description</th>
            </tr>
        </thead>
        <tbody>${tr}</tbody>
        </table>`);

        $('#ModalForm').modal({
            'backdrop': 'static',
            'show': true
        });
    })
})