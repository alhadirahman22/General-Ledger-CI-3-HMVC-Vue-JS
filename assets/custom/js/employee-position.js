$(document).ready(function () {
    // console.log();
    loadHistory();
    let area_dis = (dataModule.data.pt_sub_area_id != null &&
        dataModule.data.pt_sub_area_id != '' &&
        dataModule.data.pt_sub_area_id != 'null') ? false : true;

    $('#pt_sub_area_id').prop('disabled', area_dis);
})

$('#pt_sub_id').change(function () {

    let pt_sub_id = $('#pt_sub_id').val();

    // console.log(pt_sub_id);

    $('#pt_sub_area_id').prop('disabled', true);

    if (pt_sub_id != '' && pt_sub_id != null && pt_sub_id != 'null' && typeof pt_sub_id !== 'undefined') {

        $('#pt_sub_area_id').select2('destroy');

        let url = dataModule.module_url + 'getSubArea?pt_sub_id=' + pt_sub_id;
        $.getJSON(url, function (jsonResult) {
            // console.log(jsonResult);

            let opt = '';
            $.each(jsonResult, function (i, v) {
                opt = opt + '<option value="' + v.pt_sub_area_id + '">' + v.name + '</option>';
            });

            $('#pt_sub_area_id').html(opt);
            $('#pt_sub_area_id').select2().prop('disabled', false);

            if (jsonResult.length <= 0) {
                $('#pt_sub_area_id').prop('disabled', true);
            }

        });

    }



});

$(document).on('click', '.remove-position-history', function () {
    if (confirm('Are you sure to remove?')) {
        let employee_position_history_id = $(this).attr('data-id');
        console.log(employee_position_history_id);
        let url = dataModule.module_url + 'removePositionHistory?employee_position_history_id=' + employee_position_history_id;
        $.getJSON(url, function (response) {
            if (response.status) {
                loadHistory();
                toastr.success('Deleting', 'Success');
            }
        });
    }
});

function loadHistory() {
    $('#datamigrasi').remove();
    console.log(dataModule);
    if (typeof dataModule.data.employee_position_id !== 'undefined' && dataModule.data.employee_position_id != ''
        && dataModule.data.employee_position_id != null && dataModule.data.employee_position_id != 'null') {
        // console.log(dataModule.data.employee_position_id);
        let url = dataModule.module_url + 'getHitoryPosition?employee_position_id=' + dataModule.data.employee_position_id;
        $.getJSON(url, function (response) {
            console.log(response);
            if (response.length > 0) {

                let tr = '';
                for (let i = 0; i < response.length; i++) {
                    const d = response[i];
                    let type = (parseInt(d.is_main) == 1)
                        ? '<span class="label label-sm label-success">Main Position</span>'
                        : '<span class="label label-sm label-warning">Other Position</span>';

                    let new_tr = `<tr>
                    <td>${type}</td>
                    <td>${d.pt_name}</td>
                    <td>${d.pt_sub_name}</td>
                    <td>${d.pt_sub_area_name}</td>
                    <td>${d.department_name}</td>
                    <td>${d.position_name}</td>
                    <td>${d.job_description_name}</td>
                    <td>
                        <div class="pull-right action-buttons">
                            <a href="javascript:void(0);" data-id="${d.employee_position_history_id}" class="red remove-position-history">
                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                            </a>
                        </div>
                    </td>
                    </tr>`;

                    tr = tr + new_tr;

                }

                $('#job_description_id').parent().parent().append(`<div id="datamigrasi">
                <div class="col-md-12" style="background: #f5f5f5;margin-top:25px;">
                <h4>Migrasi / History Edit</h4>
                <table class="table table-bordered table-striped">
                <tr>
                    <td style="width:8%;">Type</td>
                    <td>PT</td>
                    <td>Sub</td>
                    <td>Area</td>
                    <td>Department</td>
                    <td>Position</td>
                    <td>Job Description</td>
                    <td style="width:3%;"></td>
                </tr>
                ${tr}
                </table>
                </div>
                </div>`);
            }
        });
    }
}