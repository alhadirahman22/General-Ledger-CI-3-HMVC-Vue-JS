$(document).ready(function () {
    // console.log(dataModule);
    $('label[for=div_sp_1]').remove();
    $('label[for=div_photo]').remove();

    loadForm();

    $('.img_preview_default').imgFitter({

        // CSS background position
        backgroundPosition: 'center center',

        // for image loading effect
        fadeinDelay: 400,
        fadeinTime: 1200

    });

    if (dataModule.action == 'edit') {
        App_template.loadingStart();
        setTimeout(() => {
            loadHistory();
            loadFormEditBefore();
            App_template.loadingEnd();
        }, 2000);
    }

});

function loadForm() {
    var url = dataModule.module_url + 'select2tags?museum_id=' + dataModule.museum_id;

    $('#tag_id').select2({
        ajax: {
            url: url,
            dataType: 'json'
        },
        minimumInputLength: 1,
    });
}

$(document).on('change', 'input[type="file"]', function (event) {
    let no = $(this).attr('data-no');
    $('#div_img_preview_' + no).remove();
    // console.log(event.target.files);

    if (event.target.files.length > 0) {
        var src = URL.createObjectURL(event.target.files[0]);

        $('#div_img_' + no).append(`
        <input name="file_exist[]" class="hide" id="file_exist_${no}" value="" />
        <div id="div_img_preview_${no}"><img data-src="${src}" id="img_preview_${no}" class="demo" width="100%" height="150">
        <button class="btn btn-xs btn-block btn-danger clear-image" data-no="${no}">Hapus</button>
        </div>
        `);


        $('#img_preview_' + no).imgFitter({

            // CSS background position
            backgroundPosition: 'center center',

            // for image loading effect
            fadeinDelay: 400,
            fadeinTime: 1200

        });

    }

});

$(document).on('click', '.clear-image', function () {
    let no = $(this).attr('data-no');
    $('#div_img_preview_' + no).remove();
    $('#file_img_' + no).val('');
});

$('#btnAddImg').click(function () {
    let total_img = $('#total_img').val();
    let new_no = parseInt(total_img) + 1;
    let pnl = `<div class="col-md-3" id="md_div_img_${new_no}">
                <div style="background:#f6f6f6;min-height:211px;margin-bottom:15px;" id="div_img_${new_no}">
                    <span class="btn btn-xs btn-block btn-raised btn-default btn-file">
                        <span class="fileinput-new"><i class="icon-upload"></i> Pilih gambar</span>
                        <input type="file" data-no="${new_no}" id="file_img_${new_no}" name="userfile[]" accept="image/*">
                    </span>
                </div>
            </div>
            `;

    $('#panel_img').append(pnl);
    $('#total_img').val(new_no);
});

$('#btnRemoveImg').click(function () {
    let total_img = $('#total_img').val();
    total_img = parseInt(total_img);
    if (total_img > 1) {
        $('#md_div_img_' + total_img).remove();
        let new_no = parseInt(total_img) - 1;
        $('#total_img').val(new_no);
    } else {
        alert('Cover tidak dapat dihapus')
    }

});

$('#attribute_group_id').change(function () {
    let attribute_group_id = $('#attribute_group_id').val();
    if (attribute_group_id != '' && attribute_group_id != null && attribute_group_id != 'null') {
        // console.log(attribute_group_id);
        let url = dataModule.module_url + 'getdetailatribut?attribute_group_id=' + attribute_group_id;
        $.getJSON(url, function (response) {
            if (response.length > 0) {
                let form = '';
                $.each(response, function (i, v) {
                    let new_form = `
                    <div class="col-md-4" style="margin-bottom: 15px;">
                    <input class="hide" value="${v.attribute_group_detail_id}" name="attribute_group_detail_id[]">
                    <div class="input-group">
                        <span class="input-group-addon input-group-addon-left">${v.attribute_name}</span>
                        <input type="${v.input_type}" name="attribute_group_detail_value[]" class="form-control">
                        <span class="input-group-addon">${v.satuan_name}</span>
                        </div></div>`;

                    form = form + new_form;
                });

                $('#show_atribut').css({
                    'background': '#f6f6f6',
                    'padding-top': '15px',
                    'padding-bottom': '15px',
                }).html(form);


            }
        })
    }
});

$('#history_id').change(function () {
    let history_id = $('#history_id').val();
    if (history_id != '' && history_id != null && history_id != 'null') {
        loadHistory();
    }
});

function loadHistory() {
    $('#view_after_history_id').remove();
    let history_id = $('#history_id').val();
    if (history_id != '' && history_id != null && history_id != 'null') {
        let url = dataModule.module_url + 'getHistory?history_id=' + history_id;
        $.getJSON(url, function (response) {
            // console.log(response);
            $('#history_id').parent().append(`
                <div id="view_after_history_id" style="margin-top:15px;">
                    <a target="_blank" href="${base_url}benda/history/form/${response.encry}" class="btn btn-xs btn-default">Buka Cerita Ditab Baru</a>
                </div>
                `);
        });
    }

}

$(document).on('keyup', 'input', async function () {
    await loadFormEditAfter();
})
$(document).on('blur', 'input', async function () {
    await loadFormEditAfter();
})

$(document).on('keyup', 'textarea', async function () {
    await loadFormEditAfter();
})
$(document).on('blur', 'textarea', async function () {
    await loadFormEditAfter();
})

function loadFormEditBefore() {

    let form = [];
    $.each(dataModule.form.form, function (i, v) {
        if (v.id != "museum_id_view" && v.id != 'div_sp_1'
            && v.id != 'div_track_history'
            && v.id != "div_photo"
            && v.type != 'hidden') {

            if (v.id == 'div_atribut') {

                let data_form = [];

                $('input[name="attribute_group_detail_id[]"]').each(function (i2, v2) {
                    let new_data_form = {
                        attribute_group_detail_id: $(this).val(),
                        attribute_group_detail_value: $(this).parent().find('input[name="attribute_group_detail_value[]"]').val(),
                        left: $(this).parent().find('.input-group-addon-left').text(),
                        right: $(this).parent().find('input[name="attribute_group_detail_value[]"]').next().text(),
                    };
                    data_form.push(new_data_form);
                });


                v.value_before = data_form;

            } else if (v.id == 'div_tags') {
                v.value_before = dataModule.data.tags;
            } else {
                if (v.class == 'select2-serverside-filter-museum' || v.class == 'select2-serverside') {
                    if (v['data-selected'] != null
                        && v['data-selected'] != '' && v['data-selected'] != 'null') {
                        v.value_before = $('#' + v.id).select2('data')[0].text;
                    } else {
                        v.value_before = '';
                    }

                } else {
                    v.value_before = v.value;
                }
            }

            if (v.html != null && typeof v.html !== 'undefined') {
                v.html = '';
            }

            form.push(v);
        }
    });

    $('#track_history').val(JSON.stringify(form));
}

function loadFormEditAfter() {
    return new Promise((resolve, reject) => {

        if (dataModule.action == 'edit') {
            let track_history = $('#track_history').val();
            if (track_history != '') {
                track_history = JSON.parse(track_history);
                let form = [];
                $.each(track_history, function (i, v) {

                    if (v.id == 'div_atribut') {

                        let data_form = [];

                        $('input[name="attribute_group_detail_id[]"]').each(function (i2, v2) {
                            let new_data_form = {
                                attribute_group_detail_id: $(this).val(),
                                attribute_group_detail_value: $(this).parent().find('input[name="attribute_group_detail_value[]"]').val(),
                                left: $(this).parent().find('.input-group-addon-left').text(),
                                right: $(this).parent().find('input[name="attribute_group_detail_value[]"]').next().text(),
                            };
                            data_form.push(new_data_form);
                        });

                        v.value_after = data_form;

                        v.is_changed = (v.value_after.length != v.value_before.length) ? 1 : 0;

                    } else if (v.id == 'div_tags') {

                        let data_multi = $('#tag_id').select2('data');
                        if (data_multi.length > 0) {
                            let text = '';
                            $.each(data_multi, function (i3, v3) {
                                let koma = (i3 != 0) ? ', ' : '';
                                text = text + koma + v3.text;
                            });
                            v.value_after = text;
                        }

                        v.is_changed = (v.value_after != v.value_before) ? 1 : 0;

                    } else {
                        if (v.class == 'select2-serverside-filter-museum' || v.class == 'select2-serverside') {
                            if ($('#' + v.id).select2('data').length > 0) {
                                v.value_after = $('#' + v.id).select2('data')[0].text;

                            } else {
                                v.value_after = '';
                            }
                        } else {
                            v.value_after = $('#' + v.id).val();
                        }

                        v.is_changed = (v.value_after != v.value_before) ? 1 : 0;
                    }
                    form.push(v);
                });

                // console.log(form);

                $('#track_history').val(JSON.stringify(form));

                resolve(1);
            }
        } else {
            resolve(1);
        }


    })
}

