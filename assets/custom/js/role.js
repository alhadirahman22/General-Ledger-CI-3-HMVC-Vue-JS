$(document).ready(function () {
    console.log(dataModule);

    var demo1 = $('select[name="permissions[]"]').bootstrapDualListbox({ infoTextFiltered: '<span class="label label-purple label-lg">Filtered</span>' });
    var container1 = demo1.bootstrapDualListbox('getContainer');
    container1.find('.btn').addClass('btn-white btn-info btn-bold');

    var demo1 = $('select[name="departments[]"]').bootstrapDualListbox({ infoTextFiltered: '<span class="label label-purple label-lg">Filtered</span>' });
    var container1 = demo1.bootstrapDualListbox('getContainer');
    container1.find('.btn').addClass('btn-white btn-info btn-bold');
});