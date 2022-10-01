
console.log(dataModule)

$(document).on('click', '.btn-submit-leave-approval', function () {
    if (confirm('Are you sure?')) {

        let id = $(this).attr('data-id');
        let status = $(this).attr('data-status');

        $(this).prop('disabled',true).html('Saving...');

        $.post(dataModule.module_url + 'hrleaveaction', { leave_id: id, status: status }, function (response) {
            // console.log(response.status);
            if (response.status == true) {
                let view_hr_status = (parseInt(status) == 1) ? '<span class="green">Approved</span>' : '<span class="red">Rejected</span>';
                $('#view_hr_status_' + id).html(view_hr_status);
                toastr.success('Data Saved', 'Success');
                setTimeout(() => {
                    $('#modal_approval').modal('hide')
                }, 1500);
            } else {
                toastr.warning('Please, try again', 'Failed');
            }
        });
    }

})

$(document).on('click', '.btn-leave-approval', function () {
    console.log('kesini');

    $('#modal_approval').remove();

    let id = $(this).attr('data-id');
    let action = $(this).attr('data-action');
    let data_leave = $('#data_leave_' + id).val();
    data_leave = JSON.parse(data_leave);
    console.log(data_leave)


    // let url = dataModule.module_url + 'getDetailEmployee?employee_id=' + employee_id;
    // $.getJSON(url, function (jsonResult) {

    // });

    // console.log(action);

    let btn_action = (action == '1' || action == 1)
        ? '<button type="button" data-id="' + id + '" data-status="1" class="btn btn-submit-leave-approval btn-success">Approve</button>'
        : '<button type="button" data-id="' + id + '" data-status="2" class="btn btn-submit-leave-approval btn-danger">Reject</button>';

    let superior_status = '<span class="blue">Wating Action</span>';
    if (parseInt(data_leave.superior_status) == 1) {
        superior_status = '<span class="green">Approved</span><div style="font-size:10px;color:#a0a0a0;">' + data_leave.superior_action_by_name + '</div>';
    } else if (parseInt(data_leave.superior_status) == 2) {
        superior_status = '<span class="red">Rejected</span><div style="font-size:10px;color:#a0a0a0;">' + data_leave.superior_action_by_name + '</div>';
    }

    let type = '<span class="green">Tidak Potong Cuti / Gaji</span>';
    if (parseInt(data_leave.type) == 1) {
        type = '<span class="blue">Potong Cuti</span>';
    } else if (parseInt(data_leave.type) == 2) {
        type = '<span class="red">Potong Gaji</span>';
    }

    let table_data = `<table class="table table-striped">
    <tr>
        <td style="width:27%;">Category</td>
        <td style="width:2%;">:</td>
        <td>${data_leave.leave_category_name}</td>
    </tr>
    <tr>
        <td>Start</td>
        <td>:</td>
        <td>${moment(data_leave.start_date).format('dddd, DD MMM YYYY HH:mm')}</td>
    </tr>
    <tr>
        <td>End</td>
        <td>:</td>
        <td>${moment(data_leave.end_date).format('dddd, DD MMM YYYY HH:mm')}</td>
    </tr>
    <tr>
        <td>Working Day</td>
        <td>:</td>
        <td>${data_leave.working_day}</td>
    </tr>
    <tr>
        <td>Reason</td>
        <td>:</td>
        <td>${data_leave.reason}</td>
    </tr>
    <tr>
        <td>Type</td>
        <td>:</td>
        <td>${type}</td>
    </tr>
    <tr>
        <td>Superior Status</td>
        <td>:</td>
        <td>${superior_status}</td>
    </tr>

    </table>`;

    let modal_body = `<div class="modal fade" id="modal_approval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">${data_leave.nik} - ${data_leave.employee_name}</h4>
            </div>
            <div class="modal-body">
            ${table_data}
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            ${btn_action}
            </div>
        </div>
        </div>
    </div>`;

    $('.page-content').append(modal_body);

    $('#modal_approval').modal({
        keyboard: false
    })

});