var config_validate = {
    submitHandler: async function (form, e) {
        if ($(form).hasClass('no-ajax')) {
            if ($(form).hasClass('target-blank')) {
                $(form).attr('target', "javascript:window.open('','targetNew')")
            }
            $('#form')[0].submit();
        } else if ($(form).hasClass('ajax-token')) {
            e.preventDefault();
            const prPost = async () => {
                var dataPost = $(form).serializeArray();

                var ArrUploadFilesSelector = [];
                var UploadFile = $('input[type="file"]');
                var valUploadFile = UploadFile.val();
                if (valUploadFile) {
                    var NameField = UploadFile.attr('name');
                    var temp = {
                        NameField: NameField,
                        Selector: UploadFile,
                    };
                    ArrUploadFilesSelector.push(temp);
                }

                const token = jwt_encode(dataPost, jwtKey);
                const url = $(form).attr('action');
                App_template.loadingStart();
                try {
                    var response = await App_template.AjaxSubmitFormPromises(url, token, ArrUploadFilesSelector);
                    reValidationFormTab(response);
                    App_template.response_form_token(response);
                } catch (err) {
                    toastr.error('something wrong, please contact administrator', '!Error');
                }

                await App_template.timeout(1000);
                App_template.loadingEnd(0);
            }

            if (confirm('Are you sure ?')) {
                prPost();
            }


        } else {
            // console.log('dataModule', dataModule);

            // if (dataModule.filter_name == "table_filter_data_benda_model" && dataModule.action == 'edit') {
            //     await loadFormEditAfter();
            // }

            if (confirm('Are you sure to edit ?')) {
                App_template.loadingStart();
                $(form).ajaxSubmit({

                    success: async function (data) {



                        try {
                            reValidationFormTab(data);
                            App_template.response_form(data);
                            await App_template.timeout(3000);
                            App_template.loadingEnd();
                        } catch (err) {
                            console.log(err);
                            await App_template.timeout(1000);
                            App_template.loadingEnd();
                        }

                    },
                    error: function () {
                        App_template.loadingEnd();
                    }
                });
            }

            return false;
        }
    }
};




const reValidationFormTab = (responseData) => {
    const selTab = $('.formClassNav');
    if (selTab.length > 0) {
        responseData = JSON.parse(responseData);

        if (responseData.fieldError !== undefined && (responseData.fieldError).length > 0) {
            const d = responseData.fieldError;
            const fieldCol = d[0];

            const form_tab_key = $('.panel-body').find('#' + fieldCol).closest('.tab-pane').attr('form-tab');
            $('.formClassNav').find('a[href="#tab-' + form_tab_key + '"]').trigger('click');
        }
    }
}
$(document).ready(function () {

    //select2
    $('.select2-serverside').each(async function (i, v) {
        var elm = $(this).attr('id');
        var table = $(this).attr('data-table');
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-text');
        var selected = $(this).attr('data-selected');

        var initialdata = [];
        if (selected != '') {
            try {
                initialdata = await select2initialselect(table, id, text, selected);
            } catch (err) { }
        }
        if (initialdata.length > 0) {
            $('#' + elm).append('<option selected="selected" value="' + initialdata[0].id + '">' + initialdata[0].text + '</option>');
        }
        var url = base_url + 'select2?table=' + table + '&id=' + id + '&text=' + text;

        $('#' + elm).select2({
            ajax: {
                url: url,
                dataType: 'json'
            },
            minimumInputLength: 3,
        });

    });

    $('.select2-serverside-filter-museum').each(async function (i, v) {
        var elm = $(this).attr('id');
        var table = $(this).attr('data-table');
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-text');
        var selected = $(this).attr('data-selected');
        var museum_id = $(this).attr('data-museumid');

        var initialdata = [];
        if (selected != '') {
            try {
                initialdata = await select2initialselect(table, id, text, selected);
            } catch (err) { }
        }
        if (initialdata.length > 0) {
            $('#' + elm).append('<option selected="selected" value="' + initialdata[0].id + '">' + initialdata[0].text + '</option>');
        }
        var url = base_url + 'select2/withfilterMuseum?table=' + table + '&id=' + id + '&text=' + text + '&museum_id=' + museum_id;

        $('#' + elm).select2({
            ajax: {
                url: url,
                dataType: 'json'
            },
            minimumInputLength: 3,
        });

    });

    $('.select2-nonserverside').select2({
        closeOnSelect: true
    });
    $('.select2-with-clear').select2({
        placeholder: '',
        allowClear: true,
        debug: true,
        closeOnSelect: true
    });

    //datepicker
    $('.date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    //multiselect
    $('.multiselect').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true
    });

    $("#form").validate(config_validate);

    // disable error validator summernote
    $('#form').each(function () {
        if ($(this).data('validator'))
            $(this).data('validator').settings.ignore = ".note-editor *";
    });

    $('.fileinput').find('a').click(function (e) {
        $('.fileinput').find('input[type="hidden"]').val(1);
    })

    $('.fileinputUpload').find('.btn-delete-file-input').click(function (e) {
        $('.fileinputUpload').find('input[type="hidden"]').val(1);
        $('.fileinputUpload').find('a').remove();
    })

    $('.area-summernote').summernote({
        placeholder: 'Text here...',
        height: 250,
        disableDragAndDrop: true,
        // airMode: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            // ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['mybutton', ['PasteHere']]
        ],
        buttons: {
            PasteHere: btnPasteHere
        },
        callbacks: {
            onImageUpload: function (image) {
                const itsme = $(this);
                var formSummernoteID = $('#formSummernoteID_' + (itsme.attr('name'))).val();
                if (formSummernoteID === undefined || formSummernoteID == '') {
                    formSummernoteID = SessionData['id'];
                }
                // summernote_UploadImage('#formQuestion',image[0],formSummernoteID);
                summernote_UploadImage_selector(itsme, image[0], formSummernoteID);
            },
            onMediaDelete: function (target) {
                summernote_DeleteImage(target[0].src);
            }
        }
    });

    $('input[data-format="maskMoney"]').maskMoney({
        thousands: '.',
        decimal: ',',
        precision: 0,
        allowZero: true
    });
    $('input[data-format="maskMoney"]').maskMoney('mask', '9894');

});

function money(amount) {
    return curr_prefix + $.number(amount, curr_decimal_digit, curr_decimal_separator, curr_thousand_separator) + curr_suffix;
}

function select2initialselect(table, id, text, selected) {
    return new Promise((resolve, reject) => {
        let url = base_url + 'select2/initialselect?table=' + table + '&id=' + id + '&text=' + text + '&selected=' + selected;
        try {
            $.getJSON(url, function (jsonResult) {
                resolve(jsonResult);
            });
        } catch (err) {
            reject('');
        }
    });
}