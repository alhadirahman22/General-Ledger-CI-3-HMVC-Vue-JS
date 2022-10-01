$(document).ready(function () {
    // console.log(dataModule);
});

$(document).on('change', '#employee_id', function () {
    let employee_id = $('#employee_id').val();
    if (employee_id != '' && employee_id != null && employee_id != 'null') {
        let url = dataModule.module_url + 'checkEmployee?employee_id=' + employee_id;
        $.getJSON(url, function (response) {
            if (response.cuti_type == 'Reguler') {

            } else {
                $("#employee_id").val(null).trigger('change');
                alert('Tipe cuti karyawan bukan Reguler');

            }
        })
    }
});



$('#minggu_kerja').change(function () {
    counttanggal();
});
$('#minggu_cuti').change(function () {
    counttanggal();
});
$('#kerja_start').change(function () {
    counttanggal();
});

function counttanggal() {
    let minggu_kerja = $('#minggu_kerja').val();
    let minggu_cuti = $('#minggu_cuti').val();
    let kerja_start = $('#kerja_start').val();

    if (minggu_kerja != '' && minggu_kerja != null && minggu_kerja != 'null' &&
        minggu_cuti != '' && minggu_cuti != null && minggu_cuti != 'null' &&
        kerja_start != '' && kerja_start != null && kerja_start != 'null') {
        $('#alert_cuti').remove();
        let url = dataModule.module_url + 'getdaterange?minggu_kerja=' + minggu_kerja + '&minggu_cuti=' + minggu_cuti + '&kerja_start=' + kerja_start;
        $.getJSON(url, function (response) {
            console.log(response);
            $('#kerja_end').val(response.kerja_end);
            $('#cuti_start').val(response.cuti_start);
            $('#cuti_end').val(response.cuti_end);

            $('#cuti_end').parent().parent().append(`<div style="font-size: 21px;background: #fffce4;padding: 15px;margin-top:20px;border: 1px solid red;" id="alert_cuti" class="col-md-12">
            - Karyawan akan bekerja selama <b>${minggu_kerja} minggu (${response.hari_kerja} hari) </b>
            dari tanggal <b>${moment(response.kerja_start).format('DD MMMM YYYY')}</b> sampai <b>${moment(response.kerja_end).format('DD MMMM YYYY')}</b>.
            <br/> - Dan mendapatkan cuti selama <b>${minggu_cuti} minggu (${response.hari_cuti} hari)</b> 
            dari tanggal <b>${moment(response.cuti_start).format('DD MMMM YYYY')}</b> sampai 
            <b>${moment(response.cuti_end).format('DD MMMM YYYY')}</b></div>`);
        });
    }

}