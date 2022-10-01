$(document).ready(function () {
    console.log(dataModule);


    loadListFamily();
    loaddatabpjs();

    loadFirstData();
});

// ================

function loadListFamily() {
    $('#family_detail_id').empty();
    let url = dataModule.module_url + 'getfamily?employee_id=' + dataModule.employee_id;
    $.getJSON(url, function (jsonResult) {
        if (jsonResult.length > 0) {
            $('#family_detail_id').append('<option value="" selected disabled>-- Select Family --</option>');
            $.each(jsonResult, function (i, v) {
                $('#family_detail_id').append('<option value="' + v.family_detail_id + '">' + v.family_name + ' - ' + v.name + '</option>');
            });
        }

        $('#dataDetail').val(JSON.stringify(jsonResult));

    });
}

$('#family_detail_id').change(function () {
    let family_detail_id = $('#family_detail_id').val();
    if (family_detail_id != '' && family_detail_id != null && family_detail_id != 'null') {
        // $.getJSON
        let dataDetail = $('#dataDetail').val();
        if (dataDetail != '') {
            dataDetail = JSON.parse(dataDetail);
            $.each(dataDetail, function (i, v) {
                if (v.family_detail_id == family_detail_id) {
                    console.log(v);
                    $('#bpjs_ks_no').val(v.bpjs_ks_no);
                    $('#faskes_1').val(v.faskes_1);
                }
            })
        }
    }
});

$('#save_bpjsks').click(function () {
    let family_detail_id = $('#family_detail_id').val();
    let bpjs_ks_no = $('#bpjs_ks_no').val();
    let faskes_1 = $('#faskes_1').val();

    if (family_detail_id != '' && family_detail_id != null && family_detail_id != 'null' &&
        bpjs_ks_no != '' && bpjs_ks_no != null && bpjs_ks_no != 'null' &&
        faskes_1 != '' && faskes_1 != null && faskes_1 != 'null') {

        let url = dataModule.module_url + 'save_familybpjs';
        $.post(url, {
                bpjs_ks_id: dataModule.bpjs_ks_id,
                family_detail_id: family_detail_id,
                bpjs_ks_no: bpjs_ks_no,
                faskes_1: faskes_1,
            },
            function (result) {
                toastr.success('Saved');
                loaddatabpjs();
            });
    }

});

function loaddatabpjs() {
    let url = dataModule.module_url + 'getfamilybpjs?bpjs_ks_id=' + dataModule.bpjs_ks_id;
    $('#listbpjs').empty();
    $.getJSON(url, function (jsonResult) {
        console.log('jsonResult', jsonResult);
        $.each(jsonResult, function (i, v) {
            $('#listbpjs').append(`<tr>
            <td>${i+1}</td>
            <td>${v.family_name}</td>
            <td>${v.family_detail_name}</td>
            <td>${v.bpjs_ks_no}</td>
            <td>${v.faskes_1}</td>
            <td><a href="javascript:void(0);" data-id="${v.bpjs_ks_detail_id}" class="red remove-bpjs-ks">
                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                </a></td>
            </tr>`);
        })
    });
}

$(document).on('click', '.remove-bpjs-ks', function () {
    if (confirm('Are you sure ?')) {
        let id = $(this).attr('data-id');
        let url = dataModule.module_url + 'delete_familybpjs';
        $.post(url, {
            id: id
        }, function (result) {});
    }
})

// ===============

function loadFirstData() {
    let total_row = $('#total_row').val();
    if (total_row != '' && total_row != null) {
        for (let i = 1; i <= total_row; i++) {
            // const element = array[i];
            select2family('#family_' + i);
        }
    }
}

$('#add_family').click(function () {

    let total_row = $('#total_row').val();
    total_row = (total_row != '' && total_row != null) ? parseInt(total_row) + 1 : 1;

    $('#list_form_bpjs').append(`<tr id="tr_${total_row}">
    <td>
        <input class="hide total_row" value="${total_row}" />
        <select class="form-control family_id" id="family_${total_row}"></select>
    </td>
    <td>
        <input class="form-control name" />
    </td>
    <td>
        <input class="form-control bpjs_ks_no" />
    </td>
    <td>
        <input class="form-control faskes_1" />
    </td>
    <td>
        <input class="form-control place_of_birth" />
    </td>
    <td>
        <input type="date" class="date_of_birth form-control" />
    </td>
    <td>
        <button class="btn btn-sm btn-block btn-danger" onclick="removerow(${total_row})"><i class="fa fa-trash"></i></button>
    </td>
    </tr>`);

    select2family('#family_' + total_row);
    $('#total_row').val(total_row);
});

function select2family(elm) {

    let module_url = $('#module_url').val();
    let url = module_url + 'select2family';

    $(elm).select2({
        ajax: {
            url: url,
            dataType: 'json'
        }
    });

}

function removerow(n) {
    $('#tr_' + n).remove();
}

$('#submit_form').click(function () {

    let next2save = true;

    let dataForm = [];

    $('#list_form_bpjs tr').each(function () {
        let total_row = $(this).find('.total_row').val();
        let family_id = $(this).find('.family_id').val();
        let name = $(this).find('.name').val();
        let bpjs_ks_no = $(this).find('.bpjs_ks_no').val();
        let faskes_1 = $(this).find('.faskes_1').val();
        let place_of_birth = $(this).find('.place_of_birth').val();
        let date_of_birth = $(this).find('.date_of_birth').val();


        $('#tr_' + total_row).css('background', '#ffffff');

        if (family_id != '' && family_id != null && family_id != 'null' &&
            name != '' && name != null && name != 'null' &&
            bpjs_ks_no != '' && bpjs_ks_no != null && bpjs_ks_no != 'null' &&
            faskes_1 != '' && faskes_1 != null && faskes_1 != 'null' &&
            place_of_birth != '' && place_of_birth != null && place_of_birth != 'null' &&
            date_of_birth != '' && date_of_birth != null && date_of_birth != 'null') {

            dataForm.push({
                family_id: family_id,
                name: name,
                bpjs_ks_no: bpjs_ks_no,
                faskes_1: faskes_1,
                place_of_birth: place_of_birth,
                date_of_birth: date_of_birth
            });

        } else {
            $('#tr_' + total_row).css('background', '#ffefed');
            toastr.warning('All forms must be filled', 'Warning');
            next2save = false;
        }

    });

    if (next2save) {

        let bpjs_ks_id = $('#bpjs_ks_id').val();

        let module_url = $('#module_url').val();
        let url = module_url + 'save_bpjs';

        $.post(url, {
            bpjs_ks_id: bpjs_ks_id,
            dataForm: dataForm
        }, function (jsonResult) {

            window.location.replace(module_url);

        });

        // $(elm).select2({
        //     ajax: {
        //         url: url,
        //         dataType: 'json'
        //     }
        // });

    }

    // $.each();
});