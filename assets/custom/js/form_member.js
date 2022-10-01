$(document).ready(function () {
    console.log(dataModule);
    getlistmep();
});

$('.show-employee').click(function () {

    let type = $(this).attr('data-type');

    $('#ModalForm .modal-title').html(`SELECT ${type.toUpperCase()}`);

    $('#ModalForm .modal-footer').html(`<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>`);

    $('#ModalForm .modal-body').html(`
    <div class="form-group">
        <input id="formsearch" class="form-control" placeholder="Search..." />
        <input id="type" class="hide" value="${type}" />
    </div>
    <hr/>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th style="width:7%;text-align:center;">
                    <i class="fa fa-cog"></i>
                </th>
            </tr>
        </thead>
        <tbody id="listemp"></tbody>
    </table>`);

    $('#ModalForm').modal({
        'backdrop': 'static',
        'show': true
    });
    serach();
});

$(document).on('keyup', '#formsearch', function () {
    serach();
});

$(document).on('click', '.btn-add-member', function () {

    $('.btn-add-member').prop('disabled', true);

    let employee_role_id = $('#employee_role_id').val();
    let type = $('#type').val();
    let employee_id = $(this).attr('data-id');

    let data = {
        employee_role_id: employee_role_id,
        employee_id: employee_id,
        type: type,
    };

    let url = dataModule.module_url + 'postempgroup';
    $.post(url, data, function (response) {
        // console.log(response);
        getlistmep();
        setTimeout(() => {
            $('#ModalForm').modal('hide');
        }, 500)
    })

});

$(document).on('click', '.remove-member', function () {

    if (confirm('Are you sure?')) {
        let employee_role_member_id = $(this).attr('data-id');
        let url = dataModule.module_url + 'removeemp?employee_role_member_id=' + employee_role_member_id;
        $.getJSON(url, function (response) {
            getlistmep();
        });
    }

});

function serach() {
    let key = $('#formsearch').val();
    let url = dataModule.module_url + 'searchemp?key=' + key;
    $.getJSON(url, function (response) {
        // console.log(response);
        $('#listemp').empty();

        let tr = '';
        $.each(response, function (i, v) {
            // console.log(v);
            let btnAction = (v.employee_role_name != '' && v.employee_role_name != null && v.employee_role_name != 'null')
                ? '' : '<button data-id="' + v.employee_id + '" class="btn btn-xs btn-success btn-block btn-add-member"><i class="fa fa-check-circle"></i></button>';
            let new_tr = `<tr>
                    <td>
                        <b>${v.nik} - ${v.name}</b>
                        <div></div>
                    </td>
                    <td>${btnAction}</td>
                </tr>`;

            tr = tr + new_tr;
        });

        $('#listemp').html(tr);

    });

}

function getlistmep() {

    let employee_role_id = $('#employee_role_id').val();
    let url = dataModule.module_url + 'getlistemp?employee_role_id=' + employee_role_id;

    $.getJSON(url, function (response) {
        // console.log(response);

        let listsuperior = '';
        let listmember = '';
        $.each(response, function (i, v) {

            let li = `<li style="padding: 3px 5px;">${v.nik} - ${v.name} | <a class="remove-member" data-id="${v.employee_role_member_id}">Remove</a></li>`;

            if (v.type == "superior") {
                listsuperior = listsuperior + li;
            } else {
                listmember = listmember + li;
            }
        });

        if (listsuperior != '') {
            $('#list_superior').html(`<ol>${listsuperior}</ol>`);
        }
        if (listmember != '') {
            $('#list_member').html(`<ol>${listmember}</ol>`);
        }

    })

}