$(document).ready(function () {
    console.log(dataModule);
})

$(document).on('click', '.show-link-history', function () {

    let history_id = $(this).attr('data-id');
    let url = dataModule.module_url + 'getLink?history_id=' + history_id;
    $.getJSON(url, function (response) {
        console.log(response);

        $('#ModalForm .modal-header').removeClass('hide');
        $('#ModalForm .modal-header .modal-title').html('Link Benda');

        $('#ModalForm .modal-footer').removeClass('hide');

        $('#ModalForm .modal-footer').html(`<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>`);

        let li = '';
        $.each(response, function (i, v) {
            let new_li = `<a href="${base_url}benda/data_benda/form/${v.encry}" target="_blank" class="list-group-item">
            <h4 class="list-group-item-heading">${v.name}</h4>
            <p class="list-group-item-text">Klik untuk melihat detail benda ditab baru</p>
            </a>`;
            li = li + new_li;
        });

        $('#ModalForm .modal-body').html(`
            <div class="row">
                <div class="col-md-12">
                    <div class="list-group">
                    ${li}
                    </div>
                </div>
            </div>
        `);

        $('#ModalForm').modal({
            'backdrop': 'static',
            'show': true
        });
    });


});