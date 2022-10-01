$(document).ready(function () {
    console.log(dataModule)
});

$('#for_all').change(function () {
    checkisforall();
});

function checkisforall() {
    let v = $('#for_all').val();
    if (v == 1) {
        $('#pt_id').prop('disabled', true).val(null).trigger('change');
        $('#pt_sub_id').prop('disabled', true).val(null).trigger('change');
        $('#pt_sub_area_id').prop('disabled', true).val(null).trigger('change');
    } else {
        $('#pt_id').prop('disabled', false);
        $('#pt_sub_id').prop('disabled', false);
        $('#pt_sub_area_id').prop('disabled', false);
    }
}