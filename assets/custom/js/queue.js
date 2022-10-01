$(function () {
    $("#sortable").sortable({
        start: function (event, ui) {
            // var start_pos = ui.item.index();
            // ui.item.data('start_pos', start_pos);
        },
        change: function (event, ui) {

        },
        update: function (event, ui) {
            $('#sortable li').removeClass('highlights');
            change_queue()
        }
    });
});


function change_queue() {
    $("#sortable li").each(function (index, ui) {
        // console.log(v,i);
        $(this).find('input.id').attr('name', 'id[' + index + ']')
        // console.log($(this).find('input[name="queue"]'));
    });
}