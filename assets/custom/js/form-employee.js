const column_name = [
    'nik', 'fingerprint_id', 'ktp_number', 'name', 'title_ahead',
    'title_behind', 'gender', 'religion_id', 'place_of_birth', 'date_of_birth',
    'blood', 'phone', 'email', 'address', 'npwp', 'join_date',
    'bank_id', 'bank_account_number', 'bank_account_name',
    'cuti', 'cuti_type', 'reguler_minggu_kerja', 'reguler_minggu_cuti',
    'poh', 'poh_sub_id',
    'education_id', 'major_id', 'employee_status_id', 'pt_id', 'pt_sub_id', 'pt_sub_area_id', 'department_id',
    'position_id', 'job_description_id',
    'clothes_id', 'clothes_size', 'clothes_received_at',
    'bpjs_tk_no', 'bpjs_tk_pt_id', 'bpjs_tk_registered_at',
    'bpjs_ks_no', 'faskes_1', 'bpjs_ks_registered_at',
    'ayah_name', 'ayah_place_of_birth', 'ayah_date_of_birth',
    'ibu_name', 'ibu_place_of_birth', 'ibu_date_of_birth',
    'suami_name', 'suami_no_bpjs', 'suami_faskes', 'suami_place_of_birth', 'suami_date_of_birth',
    'istri_name', 'istri_no_bpjs', 'istri_faskes', 'istri_place_of_birth', 'istri_date_of_birth',
    'anak1_name', 'anak1_no_bpjs', 'anak1_faskes', 'anak1_place_of_birth', 'anak1_date_of_birth',
    'anak2_name', 'anak2_no_bpjs', 'anak2_faskes', 'anak2_place_of_birth', 'anak2_date_of_birth',
    'anak3_name', 'anak3_no_bpjs', 'anak3_faskes', 'anak3_place_of_birth', 'anak3_date_of_birth',
    'contract_pt_id', 'contract_pt_sub_id', 'contract_pt_sub_area_id', 'contract_department_id',
    'contract_position_id', 'contract_job_description_id',
    'contract_employee_status_id', 'contract_start_date', 'contract_end_date'
];

const column_required = ['nik', 'fingerprint_id', 'name', 'gender', 'religion_id', 'phone', 'email', 'date_of_birth',
    'join_date', 'cuti_type', 'poh', 'poh_sub_id', 'employee_status_id', 'pt_id', 'pt_sub_id', 'department_id', 'position_id'
];

const column_master = ['religion_id', 'bank_id', 'poh', 'poh_sub_id', 'education_id', 'major_id', 'employee_status_id',
    'pt_id', 'pt_sub_id', 'pt_sub_area_id', 'department_id', 'position_id', 'job_description_id', 'clothes_id', 'bpjs_tk_pt_id',
    'contract_pt_id', 'contract_pt_sub_id', 'contract_pt_sub_area_id', 'contract_department_id',
    'contract_position_id', 'contract_job_description_id', 'contract_employee_status_id',
];
const column_table_master = ['religions', 'bank', 'pt', 'pt_sub', 'educations', 'majors', 'employee_status',
    'pt', 'pt_sub', 'pt_sub_areas', 'departments', 'positions', 'job_descriptions', 'clothes', 'pt',
    'pt', 'pt_sub', 'pt_sub_areas', 'departments', 'positions', 'job_descriptions', 'employee_status'
];
const column_table_id_master = ['religion_id', 'bank_id', 'pt_id', 'pt_sub_id', 'education_id', 'major_id', 'employee_status_id',
    'pt_id', 'pt_sub_id', 'pt_sub_area_id', 'department_id', 'position_id', 'job_description_id', 'clothes_id', 'pt_id',
    'pt_id', 'pt_sub_id', 'pt_sub_area_id', 'department_id', 'position_id', 'job_description_id', 'employee_status_id'
];

// ======= Position Form ===========

$(document).ready(function () {
    select2pt('#main_pt');
    select2department('#main_department');
    select2position('#main_position');
    checkExisiting();
});

function checkExisiting() {
    let existing_data = $('#existing_data').val();
    if (existing_data != '' && existing_data != null && existing_data != 'null') {
        existing_data = JSON.parse(existing_data);
        // console.log(existing_data);
        if (existing_data.other.length > 0) {
            $.each(existing_data.other, function (i, v) {
                select2pt('#other_pt_' + (i + 1));
                select2department('#other_department_' + (i + 1));
                select2position('#other_position_' + (i + 1));
            })
        }
    }

}

$('#add_new_position').click(function () {

    let other = $('#other').val();

    $('#list_position').append(`<tr id="new_tr_${other}">
    <td>Other Position</td>
    <td><select id="other_pt_${other}" class="form-control fm-pt"></select></td>
    <td><select id="other_department_${other}" class="form-control fm-department"></select></td>
    <td><select id="other_position_${other}" class="form-control fm-position"></select></td>
    <td>
        <button class="btn btn-sm btn-danger btn-block" onclick="remove_form(${other})"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
    </td>
    </tr>`);

    select2pt('#other_pt_' + other);
    select2department('#other_department_' + other);
    select2position('#other_position_' + other);

    $('#other').val((parseFloat(other) + 1));
});

function remove_form(n) {
    $('#new_tr_' + n).remove();
}

function select2pt(elm) {

    let module_url = $('#module_url').val();
    let url = module_url + 'select2pt';

    $(elm).select2({
        ajax: {
            url: url,
            dataType: 'json'
        }
    });

}

function select2department(elm) {

    let module_url = $('#module_url').val();
    let url = module_url + 'select2department';

    $(elm).select2({
        ajax: {
            url: url,
            dataType: 'json'
        }
    });

}

function select2position(elm) {

    let module_url = $('#module_url').val();
    let url = module_url + 'select2position';

    $(elm).select2({
        ajax: {
            url: url,
            dataType: 'json'
        }
    });

}

$('#submit_position').click(function () {

    let main_pt = $('#main_pt').val();
    let main_department = $('#main_department').val();
    let main_position = $('#main_position').val();

    if (main_pt != '' && main_pt != null && main_pt != 'null' &&
        main_department != '' && main_department != null && main_department != 'null' &&
        main_position != '' && main_position != null && main_position != 'null') {

        let next2save = true;

        let others = [];

        $('#list_position tr').each(function () {

            $(this).css('background', 'none');

            var pt = $(this).find('.fm-pt').val();
            var department = $(this).find('.fm-department').val();
            var position = $(this).find('.fm-position').val();

            if (pt != '' && pt != null && pt != 'null' &&
                department != '' && department != null && department != 'null' &&
                position != '' && position != null && position != 'null') {
                others.push({
                    pt_id: pt,
                    department_id: department,
                    position_id: position,
                });
            } else {
                next2save = false;
                $(this).css('background', '#fdf8cf');
            }

        });

        // console.log(others);

        if (next2save) {

            let module_url = $('#module_url').val();

            let employee_id = $('#employee_id').val();

            let url = module_url + 'save_position';
            let data = {
                employee_id: employee_id,
                main: {
                    pt_id: main_pt,
                    department_id: main_department,
                    position_id: main_position,
                },
                others: others
            };

            $.post(url, data, function (jsonResult) {
                let d = JSON.parse(jsonResult);
                // console.log(d);
                window.location.replace(d.redirect);
            });

        } else {
            toastr.warning('Please complete another position form or delete it', 'Warning');
        }


    } else {
        toastr.warning('Main Position is Required', 'Warning');
    }

});

// ======== Employees Bulk =========

$(document).on('click', '#upload', function () {
    doactionExcel();
});

function doactionExcel() {

    $('#dvExcel').empty();
    $('#rowTotalExcel').val(0);

    $('#btnSubmitBulk').prop('disabled', true);
    $('#errorList').empty();

    // $('body').append('<div class="loading">Loading&#8230;</div>');

    // setTimeout(() => {
    //     $('.loading').remove();
    // }, 5000);

    //Reference the FileUpload element.
    var fileUpload = $("#fileUpload")[0];
    //Validate whether File is valid Excel file.
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
    if (regex.test(fileUpload.value.toLowerCase())) {
        if (typeof (FileReader) != "undefined") {
            var reader = new FileReader();

            //For Browsers other than IE.
            if (reader.readAsBinaryString) {
                reader.onload = function (e) {
                    ProcessExcel(e.target.result);
                };
                reader.readAsBinaryString(fileUpload.files[0]);
            } else {
                //For IE Browser.
                reader.onload = function (e) {
                    var data = "";
                    var bytes = new Uint8Array(e.target.result);
                    // console.log('byteLength',bytes.byteLength);
                    for (var i = 0; i < bytes.byteLength; i++) {
                        data += String.fromCharCode(bytes[i]);
                    }
                    ProcessExcel(data);
                };
                reader.readAsArrayBuffer(fileUpload.files[0]);
                // $('.loading').remove();
            }
        } else {
            alert("This browser does not support HTML5.");
        }
    } else {
        alert("Please upload a valid Excel file.");
    }
}

async function ProcessExcel(data) {

    //Read the Excel File data.
    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    //Fetch the name of First Sheet.
    var firstSheet = workbook.SheetNames[0];

    //Read all rows from First Sheet into an JSON array.
    var excelRows_data = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

    var excelRows = $.map(excelRows_data, function (value, index) {
        return [value];
    });

    // console.log(excelRows);

    //Create a HTML Table element.
    var table = $("<table />");
    // table[0].border = "1";
    table[0].className = "table table-bordered table-striped";

    //Add the header row.
    var row = $(table[0].insertRow(-1));

    //Add the header cells.
    for (let i = 0; i < column_name.length; i++) {
        const element = column_name[i];

        var headerCell = $("<th />");
        headerCell.html("" + element);
        row.append(headerCell);
    }

    // console.log(excelRows.length);

    // 100 / 5

    let default_progress_value = 100 / excelRows.length;
    let progress_value = 0;

    let viewTotalEmployee = 0;

    $('#rowTotalExcel').val(excelRows.length);

    if (excelRows.length <= 100) {

        let unablebutton = true;

        let temp_nik = [];
        let temp_finger = [];
        let temp_ktp = [];
        let temp_phone = [];
        let temp_email = [];

        //Add the data rows from Excel file.
        for (var i = 0; i < excelRows.length; i++) {


            //Add the data row.
            var row = $(table[0].insertRow(-1));

            for (let c = 0; c < column_name.length; c++) {

                var is_read_only = '';
                var imageEmp = '';

                const element = column_name[c];

                var value_column = excelRows[i]['' + element];
                var name_value_column = excelRows[i]['name'];

                value_column = (typeof value_column == 'string') ? value_column.trim() : value_column;

                value_column = (typeof value_column !== 'undefined' && value_column != 'BLANK') ? value_column : '';

                // cek apakah form required atau tidak
                if ($.inArray(element, column_required) != -1) {

                    if (value_column == '') {

                        $('#fileUpload').val('');
                        $('#errorList').append('<li>' + name_value_column + ' | ' + element + ' tidak boleh kosong</li>');
                        unablebutton = false;
                    } else {
                        is_read_only = 'readonly';
                    }

                }

                // cek duplikasi nik
                if (element == 'nik') {

                    if ($.inArray(value_column, temp_nik) != -1) {

                        $('#fileUpload').val('');
                        $('#errorList').append('<li>' + name_value_column + ' | ' + value_column + ' duplikasi NIK</li>');
                        unablebutton = false;
                    } else {

                        // cek nik
                        try {
                            var cknikindb = await checkExistingNik(value_column);

                            if (cknikindb) {
                                imageEmp = await checkImage(value_column);
                                imageEmp = (imageEmp != '') ? '<img style="width:100%;" src="' + imageEmp + '" />' : '';
                                temp_nik.push(value_column);
                            } else {
                                $('#fileUpload').val('');
                                $('#errorList').append('<li>' + name_value_column + ' | NIK sudah terdaftar di database</li>');
                                unablebutton = false;
                            }

                        } catch (err) {
                            $('#fileUpload').val('');
                            $('#errorList').append('<li>' + name_value_column + ' | Masalah saat pengecekan NIK dengan DB</li>');
                            unablebutton = false;
                        }

                    }
                }

                // cek duplikasi finger
                if (element == 'fingerprint_id') {
                    if ($.inArray(value_column, temp_finger) != -1) {
                        $('#fileUpload').val('');
                        $('#errorList').append('<li>' + name_value_column + ' | ' + value_column + ' duplikasi Fingerprint ID</li>');
                        unablebutton = false;
                    } else {
                        temp_finger.push(value_column);
                    }
                }

                // cek duplikasi ktp
                if (element == 'ktp_number') {
                    if ($.inArray(value_column, temp_ktp) != -1) {
                        $('#fileUpload').val('');
                        $('#errorList').append('<li>' + name_value_column + ' | ' + value_column + ' duplikasi KTP</li>');
                        unablebutton = false;
                    } else {
                        temp_ktp.push(value_column);
                    }
                }

                // phone number
                if (element == 'phone') {
                    if ($.inArray(value_column, temp_phone) != -1) {
                        $('#fileUpload').val('');
                        $('#errorList').append('<li>' + name_value_column + ' | ' + value_column + ' duplikasi Phone</li>');
                        unablebutton = false;
                    } else {
                        temp_phone.push(value_column);
                    }
                }

                // temp_email number
                if (element == 'email') {
                    if ($.inArray(value_column, temp_email) != -1) {
                        $('#fileUpload').val('');
                        $('#errorList').append('<li>' + name_value_column + ' | ' + value_column + ' duplikasi Email</li>');
                        unablebutton = false;
                    } else {
                        temp_email.push(value_column);
                    }
                }

                // get_master column_master
                if ($.inArray(element, column_master) != -1) {
                    value_column = await get_master(column_table_master[$.inArray(element, column_master)],
                        column_table_id_master[$.inArray(element, column_master)], value_column);

                    is_read_only = 'readonly';
                }

                var cell = $("<td />");
                cell[0].className = 'td-data';

                var imttd = '<input name="' + element + '[' + i + ']" data-name="' + element + '" class="form-control form-' + i + '" ' + is_read_only + ' value="' + value_column + '" />';
                var formInput = (imageEmp != '')
                    ? '<div class="row"><div class="col-md-3">' + imageEmp + '</div><div class="col-md-9">' + imttd + '</div></div>'
                    : imttd;

                cell.html(formInput);
                row.append(cell);

            }

            progress_value = progress_value + default_progress_value;

            viewTotalEmployee = viewTotalEmployee + 1;

            $('#excel_progress').attr('aria-valuenow', progress_value);
            $('#excel_progress').css('width', progress_value + '%');
            $('#excel_progress').html(Math.round(progress_value) + '%');

            $('#viewTotalEmployee').html(viewTotalEmployee);

        }

        if (unablebutton) {
            var dvExcel = $("#dvExcel");
            dvExcel.html("");
            dvExcel.append(table);

            $('#btnSubmitBulk').prop('disabled', false);
        }



    } else {
        alert('Maksimum data 100 row!');
    }

    // App_template.loadingEnd(0);

};

function get_master(table, id, value) {

    return new Promise((resolve, reject) => {

        try {

            let module_url = $('#module_url').val();
            let url = module_url + 'get_master';

            $.post(url, {
                table: table,
                id: id,
                value: value
            }, function (result) {
                // if(jsonResult.)
                if (result != '') {
                    resolve(value + '|' + result);
                } else {
                    resolve('');
                }

            });

        } catch (error) {
            reject('error_' + table);
        }

    });


}

function checkExistingNik(nik) {
    return new Promise((resolve, reject) => {
        let module_url = $('#module_url').val();
        let url = module_url + 'ceknik';
        $.post(url, {
            nik: nik
        }, function (result) {
            // if(jsonResult.)
            resolve(result);
        });

    });
}

function checkImage(nik) {
    return new Promise((resolve, reject) => {
        let module_url = $('#module_url').val();
        let url = module_url + 'cekImage';
        $.post(url, {
            nik: nik
        }, function (result) {
            resolve(result);
        });
    });
}

function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

async function submitbulk() {

    App_template.loadingStartWithProgress();

    let rowTotalExcel = $('#rowTotalExcel').val();
    console.log(rowTotalExcel);

    let progressStart = 100 / rowTotalExcel;
    let progress = 0;

    for (let i = 0; i < rowTotalExcel; i++) {
        // const element = array[i];
        let data = {};
        $('.form-' + i).each(function (i, v) {
            // console.log(i, v);

            var name = $(this).attr('data-name');
            var v = $(this).val();

            // console.log(name);

            if ($.inArray(name, column_master) != -1 && v != '') {
                v = v.split('|')[0];
            }

            data[name] = v;

            // data.push({ ''+name: v });
        });
        await savingBulk(data);

        progress = progress + progressStart;
        $('#dataLoadingStartWithProgress').html(Math.round(progress));
    }

    App_template.loadingEnd(0);

    setTimeout(() => {
        let module_url = $('#module_url').val();
        window.location.replace(module_url);
    }, 1000)

    // console.log(formSubmit);
}

function savingBulk(data) {

    return new Promise((resolve, reject) => {
        try {

            console.log(data);
            let module_url = $('#module_url').val();
            let url = module_url + 'saviBulk2';

            $.post(url, data, function (result) {

                resolve(1);

            })

        } catch (err) {
            reject(0);
        }
    });



}