class Class_template_table_default {
    constructor() {
        let filter_2_length = $('#filter_2_length').val();
        this.param = [];
        this.param.table_config = {
            responsive: {
                details: {
                    type: 'column'
                }
            },
            processing: true,
            serverSide: true,
            lengthMenu: [
                10, 25, 50, 75, 100, 150, 200
            ],
            iDisplayLength: (filter_2_length != '' && filter_2_length != null && typeof filter_2_length !== 'undefined') ? parseInt(filter_2_length) : 10,
            "searching": false,
            ajax: {
                url: $('#table_default').attr('data-url'),
                type: 'post',
                data: function (d) {
                    let filterTable = {}
                    $('.filterSearch select[tabindex="-1"], .filterSearch input').each(function () {
                        filterTable[$(this).attr("name")] = $(this).val();
                    });
                    d.filter = filterTable;


                    let result = [];
                    $('.custom-filter').each(function () {
                        var key = $(this).attr('data-key');
                        var value = $(this).val();
                        if (value != '' && value != null && value != 'null' && typeof value !== 'undefined') {
                            result.push({
                                key: key,
                                value: value
                            });
                        }

                    });

                    // console.log(result);
                    d.filter_custom = result;

                    return d;
                },
            },
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                targets: 'no-sort'
            }, {
                className: 'text-center',
                targets: 'text-center'
            }, {
                className: 'text-right',
                targets: 'text-right'
            }, {
                className: 'no-padding',
                targets: 'no-padding'
            }, {
                className: 'td-no-padding',
                targets: 'td-no-padding'
            }, {
                className: 'th-no-padding',
                targets: 'th-no-padding'
            }, {
                className: 'td-image',
                targets: 'image'
            }, {
                visible: false,
                targets: 'hide'
            }],
            order: [
                [$('#table_default th.default-sort').index(), $('#table_default th.default-sort').attr('data-sort')]
            ],
            orderCellsTop: true,
            "initComplete": function (settings, json) {

            },

        };
    }

    load_default = () => {
        $('.filterSearch').find('select[tabindex!="-1"]').removeClass('form-control');
        $('.filterSearch').find('select[tabindex!="-1"]').removeClass('form-control-sm');
        $('.filterSearch').find('select[tabindex!="-1"]').select2({
            allowClear: true
        });
    }

    dataTableGenerate = () => {
        var columns = [];
        $('#table_default .column th').each(function () {
            columns.push({
                data: $(this).attr('data-data')
            });
        });
        this.param.table_config.columns = columns;
        let filter_name = $('#table_default').attr('data-filter-name');
        // console.log(filter_name);
        this.param.sel_table = $('#table_default').DataTable(this.param.table_config);

        let filter_2_page = $('#filter_2_page').val();
        if (filter_2_page != '' && filter_2_page != null && typeof filter_2_page !== 'undefined') {
            this.param.sel_table.on('init.dt', (e) => {
                this.param.sel_table.page(parseInt(filter_2_page)).draw(false);
            });
        }

        $('#table_default_length').parent().next().append(`<div class="pull-right">
        <button onclick="unsetfilter('${filter_name}')" class="btn btn-white btn-warning btn-sm"><i class="fa fa-refresh" style="margin-right:5px;"></i> Reset Filter</button></div>`);
        // console.log('<?= $filter_name ?>');

    }
}

const template_table_default = new Class_template_table_default();

$(document).ready(function (e) {
    $('.custom-filter').select2();
    template_table_default.load_default();
    template_table_default.dataTableGenerate();

    let tbl = template_table_default.param.sel_table;

    $('body').on('keyup', '.filterSearch input', function (e) {
        if (e.keyCode == 13) {
            tbl.ajax.reload((e) => {

            });
        }
    });

    $('body').on('change', '.filterSearch input[type="date"]', function (e) {
        tbl.ajax.reload((e) => {

        });
    });

    $('body').on('change', '.filterSearch select', function (e) {
        tbl.ajax.reload((e) => {

        });
    });

    $('body').on('click', '.delete_row_default', function (e) {

        e.preventDefault();
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirm!',
            content: 'Are you sure want to delete ?',
            type: 'red',
            typeAnimated: true,
            autoClose: 'cancel|8000',
            buttons: {
                cancel: {
                    btnClass: 'btn-default',
                    action: () => {
                        $.alert('Canceled!');
                    }
                },
                confirm: {
                    btnClass: 'btn-danger',
                    action: () => {
                        var get_url = $(this).attr('href');
                        delete_row_default(get_url, tbl)
                    },
                },
            }
        });



        // if (confirm('Are you sure want to delete ?')) {
        //     delete_row_default(get_url,tbl)
        // }
    });

    $('body').on('click', '.custom_row_action', function (e) {

        e.preventDefault();
        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirm!',
            content: 'Are you sure ?',
            type: 'red',
            typeAnimated: true,
            autoClose: 'cancel|8000',
            buttons: {
                cancel: {
                    btnClass: 'btn-default',
                    action: () => {
                        $.alert('Canceled!');
                    }
                },
                confirm: {
                    btnClass: 'btn-info',
                    action: () => {
                        var get_url = $(this).attr('href');
                        custom_row_action(get_url, tbl)
                    },
                },
            }
        });

    });

    $('body').on('click', '.btn-details', function (e) {
        const itsme = $(this);
        const data_token = jwt_decode(itsme.attr('data-token'));
        _details(data_token);

    });

    $('body').on('click', '.detail-history-item', function (e) {
        const itsme = $(this);
        const data_token = jwt_decode(itsme.attr('data-token'));
        _detail_history_item(data_token);

    });

    $(document).on('change', '.custom-filter', function () {
        tbl.ajax.reload((e) => {

        });
    })

})

const _detail_history_item = (data_token) => {
    console.log(data_token);
    var data = data_token.data;
    var datasHistory = data_token.datasHistory;
    $('#ModalForm .modal-title').html('<h4>History - ' + data['warehouse_sub_name'] + ' | ' + data['item_name'] + '</h4>');
    $('#ModalForm .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

    var tr = '';
    $.each(datasHistory, function (i, v) {
        console.log(i);
        console.log(v);

        var pref = (v.type == '1') ? '+' : '-';
        var color = (v.type == '1') ?
            'green' :
            'red';

        var newTr = `<tr>
                <td>${i + 1}</td>
                <td style="text-align:right;"><b class="${color}">${pref} ${v.total_item}</b></td>
                <td style="text-align:left;">${v.note}</td>
                <td style="text-align:left;">${moment(v.created_at).format('ddd, DD MMM YYYY HH:mm')}</td>
                <td>${v.created_by_username}</td>
            </tr>`;

        tr = tr + newTr

    });


    var html = `<table class="table table-bordered table-center table-striped">
    <thead>
        <tr>
            <td style="width:1%;">No</td>
            <td style="width:10%;">Stock</td>
            <td>Note</td>
            <td style="width:25%;">Date</td>
            <td style="width:15%;">User</td>
        </tr>
    </thead><tbody>${tr}</tbody>
    </table>`;
    $('#ModalForm .modal-body').html(html);

    $('#ModalForm').modal({
        'show': true,
        'backdrop': 'static'
    });
}

const _details = (data_token) => {
    console.log(data_token);
    // details modal
    if (typeof get_language !== 'undefined') {
        // the variable is defined
        $('#ModalForm .modal-title').html('<h4>Detail ' + data_token['name_header'] + '</h4>');
        $('#ModalForm .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        const lang_details = Object.keys(get_language).filter((key) => data_token.hasOwnProperty(key)).reduce((obj, key) => {
            obj[key] = get_language[key];
            return obj;
        }, {});

        let html = '<div class = "row">' +
            '<div class = "col-md-12">' +
            '<table class = "table">' +
            '<thead>' +
            '<tr>' +
            '<td>' + get_language['label'] + '</td>' +
            '<td>' + get_language['value'] + '</td>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';

        html += Object.keys(lang_details).map(element => {
            return '<tr>' +
                '<td>' + lang_details[element] + '</td>' +
                '<td>' + data_token[element] + '</td>' +
                '</tr>';
        }).join('');

        html += '</tbody></table></div></div>';

        $('#ModalForm .modal-body').html(html);

        $('#ModalForm').modal({
            'show': true,
            'backdrop': 'static'
        });

    }
}

const delete_row_default = async (get_url, tbl) => {
    try {
        var response = await App_template.AjaxSubmitFormPromises(get_url, '');
        if (response.status == 'success') {
            tbl.ajax.reload((e) => {

            });
            success_message(response.message);
        } else {
            error_message(response.message);
        }

    } catch (err) {
        console.log(err);
        toastr.error('something wrong, please contact IT', '!Error');
    }
}

const custom_row_action = async (get_url, tbl) => {
    try {
        var response = await App_template.AjaxSubmitFormPromises(get_url, '');
        if (response.status == 'success') {
            tbl.ajax.reload((e) => {

            });
            success_message(response.message);
        } else {
            error_message(response.message);
        }

    } catch (err) {
        console.log(err);
        toastr.error('something wrong, please contact IT', '!Error');
    }
}

$('.show-list-museum').click(function () {
    $('#ModalFormSmall .modal-header').removeClass('hide');
    $('#ModalFormSmall .modal-header .modal-title').html('Pilih Museum');

    $('#ModalFormSmall .modal-footer').removeClass('hide');

    $('#ModalFormSmall .modal-footer').html(`
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-sm btn-primary" id="submit_to_add_benda">Kirim</button>
    `);

    let options = '';
    $.each(dataModule.data_museum, function (i, v) {
        options = options + '<option value="' + v.encry + '">' + v.name + '</option>';
    });

    $('#ModalFormSmall .modal-body').html(`
    <div class="row">
        <div class="col-md-12">
            <select class="form-control" id="museum_id_encry">${options}</select>
        </div>
    </div>
    `);

    $('#ModalFormSmall').modal({
        'backdrop': 'static',
        'show': true
    });
});

$(document).on('click', '#submit_to_add_benda', function () {
    let museum_id_encry = $('#museum_id_encry').val();
    window.location.href = dataModule.module_url + 'form/' + museum_id_encry;
});