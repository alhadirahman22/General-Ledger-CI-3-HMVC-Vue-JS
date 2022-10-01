$(document).ready(function () {
    console.log(dataModule);
    if (typeof dataModule.data.cuti_type !== 'undefined') {
        cutitype(dataModule.data.reguler_minggu_kerja, dataModule.data.reguler_minggu_cuti);
    }
});

$('#cuti_type').change(function () {
    cutitype('', '');
});

function cutitype(reguler_minggu_kerja, reguler_minggu_cuti) {
    let cuti_type = $('#cuti_type').val();

    if (cuti_type == 'Reguler') {

        $('#cuti_type').parent().append(`<div id="reguler_form" class="well"><div class="row">
            <div class="col-md-6">
                <div class="form-group">    
                    <label><i>Jumlah Minggu Kerja</i></label>
                    <input type="number" name="reguler_minggu_kerja" id="reguler_minggu_kerja" value="${reguler_minggu_kerja}" class="form-control" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">    
                    <label><i>Jumlah Minggu Cuti</i></label>
                    <input type="number" name="reguler_minggu_cuti" id="reguler_minggu_cuti" value="${reguler_minggu_cuti}" class="form-control" />
                </div>
            </div>
            </div>
            </div>`);

    } else {
        $('#reguler_form').remove();
    }
    console.log(cuti_type);
}

$('#employee_status_active').change(function () {
    let employee_status_active = $('#employee_status_active').val();
    // console.log('employee_status_active', employee_status_active);
    if (parseInt(employee_status_active) != 1) {
        $('#resign_date').prop('disabled', false);
    } else {
        $('#resign_date').prop('disabled', true).val('');
    }
})