$(document).ready(function () {

    console.log(dataModule);

});

$(document).on('click', '.viewsunting', function () {

    let benda_id = $(this).attr('data-id');
    let url = dataModule.module_url + 'getListSunting?benda_id=' + benda_id;

    $.getJSON(url, function (response) {
        console.log(response);

        $('#ModalFormSmall .modal-header').removeClass('hide');
        $('#ModalFormSmall .modal-header .modal-title').html('Riwayat Sunting');

        $('#ModalFormSmall .modal-footer').removeClass('hide');

        $('#ModalFormSmall .modal-footer').html(`<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>`);

        let options = '';
        $.each(response, function (i, v) {
            let new_opt = `<a href="${dataModule.module_url}form_changed/${v.encrypt}" target="_blank" class="list-group-item">
                        <h4 class="list-group-item-heading">${moment(v.created_at).format('dddd, DD MMM YYYY HH:mm')}</h4>
                        <p class="list-group-item-text">...</p>
                    </a>`;
            options = options + new_opt;
        });

        $('#ModalFormSmall .modal-body').html(`
            <div class="row">
                <div class="col-md-12">
                    <div class="list-group">
                    ${options}
                    </div>
                </div>
            </div>
            `);

        $('#ModalFormSmall').modal({
            'backdrop': 'static',
            'show': true
        });

    });



});