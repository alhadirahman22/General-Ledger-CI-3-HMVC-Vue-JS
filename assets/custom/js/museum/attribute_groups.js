$(document).ready(function () {
    console.log(dataModule);
});

$('.show-list-group').click(function () {


    $('#ModalForm .modal-header').removeClass('hide');
    $('#ModalForm .modal-header .modal-title').html('Group Atribut');
    $('#ModalForm .modal-footer').removeClass('hide');
    $('#ModalForm .modal-dialog').removeClass('modal-sm modal-lg');
    $('#ModalForm .modal-dialog').addClass('modal-lg');

    $('#ModalForm .modal-footer .btn-primary').addClass('hide');

    let options = '<option disabled selected>-- Pilih Museum --</option>';
    $.each(dataModule.data_museum, function (i, v) {
        options = options + '<option value="' + v.museum_id + '">' + v.name + '</option>';
    })


    $('#ModalForm .modal-body').html(`
        <div class="row" style="margin-bottom:15px;">
            <div class="col-md-6 col-md-offset-3">
                <select id="optionMuseum" class="form-control">${options}</select>
            </div>
        </div>
        <div id="showMuseum"></div>`);



    $('#ModalForm').modal({
        'backdrop': 'static',
        'show': true
    });

});

$(document).on('change', '#optionMuseum', function () {
    $('#showMuseum').html('');
    // alert('sini');
    let museum_id = $('#optionMuseum').val();
    if (museum_id != '' && museum_id != null) {
        let url = dataModule.module_url + 'getgroup?museum_id=' + museum_id;
        $.getJSON(url, function (response) {
            let tr = '<tr><td colspan="8" style="text-align:center;">- - No data - -</td></tr>';
            if (response.length > 0) {
                tr = '';
                $.each(response, function (i, v) {
                    // console.log(v);

                    var new_tr = `<tr>
                <td>${i + 1}</td>
                <td>${v.museum_name}</td>
                <td>${v.name}</td>
                <td>${v.total_attribute}</td>
                <td>${v.descriptions}</td>
                <td>
                    <div class="hidden-sm hidden-xs action-buttons" style="text-align:center;">
                        <a class="green" href="${dataModule.module_url}form/${v.encry}">
                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                        </a>
                        <a class="red remove_data" href="javascript:void(0)" data-url="${dataModule.module_url}delete/${v.encry}">
                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                        </a>
                    </div>
                    <hr style="margin-top: 5px;margin-bottom: 5px;"/>
                    <a href="${dataModule.module_url}form_details/${v.encry}">Tambah Atribut</a>
                </td>
                </tr>`;

                    tr = tr + new_tr;
                });

                $('#showMuseum').html(`<table id="tablemuseum" class="table table-bordered" style="width:100% !important;">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:10%;">Museum</th>
                        <th style="width:10%;">Group</th>
                        <th style="width:10%;">Total Atribut</th>
                        <th>Deskripsi</th>
                        <th style="width:13%;"></th>
                    </tr>
                </thead>
                <tbody>${tr}</tbody>
                </table>`);
            }
        });
    }

})

$(document).on('click', '.remove_data', async function () {
    if (confirm('Are you sure?')) {
        let get_url = $(this).attr('data-url');
        try {

            var response = await App_template.AjaxSubmitFormPromises(get_url, '');
            if (response.status == 'success') {
                $('#ModalForm').modal('hide');
                success_message(response.message);
            } else {
                error_message(response.message);
            }

        } catch (err) {
            console.log(err);
            toastr.error('something wrong, please contact IT', '!Error');
        }
    }
})