$(document).ready(function () {
    console.log(dataModule);
});

$(document).on('change', '#group_id', function () {
    let group_id = $('#group_id').val()
    if (group_id != '' && group_id != null && group_id != 'null') {
        let url = dataModule.module_url + 'getDetailGroup?group_id=' + group_id;
        $.getJSON(url, function (response) {
            console.log(response);
            $('#group_id').parent().append(`<div style="min-height:50px;background:#f5f5f5;">
            <div class="row" style="padding: 7px 10px 5px 10px;">
                <div class="col-md-3">Group</div>
                <div class="col-md-9">: ${response.name}</div>
            </div>
            <div class="row" style="padding: 7px 10px 5px 10px;">
                <div class="col-md-3">Employee</div>
                <div class="col-md-9">: ${response.employee_name}</div>
            </div>
            </div>`);
        });
    }
})

// ======== Untuk List ========
$(document).on('click', '.btn-list-group', function () {

    let url = dataModule.module_url + 'getListGroup';
    $.getJSON(url, function (response) {
        console.log(response);

        $('#ModalForm .modal-header').removeClass('hide');
        $('#ModalForm .modal-header .modal-title').html('List of Group Employee');
        $('#ModalForm .modal-footer').removeClass('hide');
        $('#ModalForm .modal-dialog').removeClass('modal-sm modal-lg');
        $('#ModalForm .modal-dialog').addClass('modal-lg');
        $('#ModalForm .modal-footer .btn-primary').addClass('hide');

        let tr = '';
        if (response.length > 0) {

            // <a class="red" href="#">
            //                 <i class="ace-icon fa fa-trash-o bigger-130"></i>
            //             </a>

            $.each(response, function (i, v) {
                let new_tr = `<tr>
                <td style="text-align:center;">${i + 1}</td>
                <td>${v.name}</td>
                <td>${v.employee_name}</td>
                <td style="text-align:center;">${v.total_member}</td>
                <td style="text-align:center;">
                    <div class="hidden-sm hidden-xs action-buttons">
                        <a class="green" href="${dataModule.module_url}form/${v.encry}">
                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                        </a>
                    </div>
                </td>
                </tr>`;
                tr = tr + new_tr;
            })
        }




        $('#ModalForm .modal-body').html(`<table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="text-align:center;width:3%;">No</th>
                <th style="text-align:center;">Name</th>
                <th style="text-align:center;">Employee</th>
                <th style="text-align:center;">Total Member</th>
                <th style="text-align:center;width:10%;">Act</th>
            </tr>
        </thead>
        <tbody>${tr}</tbody>
        </table>
        `);

        $('#ModalForm').modal({
            'backdrop': 'static',
            'show': true
        });

    });



});