$(document).ready(function () {
    console.log(dataModule)
});

var url = dataModule.module_url + 'select2attribute?museum_id=' + dataModule.museum_id + '&attribute_group_id=' + dataModule.attribute_group_id;

$('#attribute_id').select2({
    ajax: {
        url: url,
        dataType: 'json'
    },
    minimumInputLength: 3,
});

$(document).on('click', '.remove-att-group-detail', function () {
    if (confirm('Apakah anda yakin ?')) {
        let attribute_group_detail_id = $(this).attr('data-id');
        var url = dataModule.module_url + 'removeGroupDetails?attribute_group_detail_id=' + attribute_group_detail_id;
        $.getJSON(url, function (response) {
            $('#attribute_group_detail_id_' + attribute_group_detail_id).remove();
        })
    }
});