// JS created by Alhadi Rahman

// for modal datatable
var modal_table_config = {};
var filterTableModal = {};
// for modal datatable

moment.locale('id')

function success_message(message) {
    console.log(message);
    toastr.success(message);
}

function error_message(message) {
    console.log(message);
    toastr.error(message, '!error');
}

function Validation_leastCharacter(leastNumber, string, theName) {
    var result = { status: 1, messages: "" };
    var stringLenght = string.length;
    if (stringLenght < leastNumber) {
        result = { status: 0, messages: theName + " at least " + leastNumber + " character" };
    }
    return result;
}

function Validation_email(string, theName) {
    var result = { status: 1, messages: "" };
    var regexx = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (!string.match(regexx)) {
        result = { status: 0, messages: theName + " an invalid email address! " };
    }
    return result;
}

function Validation_email_gmail(string, theName) {
    var result = { status: 1, messages: "" };
    var regexx = /^[a-z0-9](\.?[a-z0-9]){5,}@g(oogle)?mail\.com$/;
    if (!string.match(regexx)) {
        result = { status: 0, messages: theName + " only gmail allowed to register! " };
    }
    return result;
}

function Validation_required(string, theName) {
    var result = { status: 1, messages: "" };
    if (string == "" || string == null) {
        result = { status: 0, messages: theName + " is required! " };
    }
    return result;
}

function Validation_numeric(string, theName) {
    var result = { status: 1, messages: "" };
    var regexx = /^\d+$/;
    if (!string.match(regexx)) {
        result = { status: 0, messages: theName + " only numeric! " };
    }
    return result;
}

function Validation_decimal(string, theName) {
    var result = { status: 1, messages: "" };
    var regexx = /^\d*\.?\d*$/;
    if (!string.match(regexx)) {
        result = { status: 0, messages: theName + " only decimal! " };
    }
    return result;
}

// for text area
function nl2br(str, replaceMode, isXhtml) {

    var breakTag = (isXhtml) ? '<br />' : '<br>';
    var replaceStr = (replaceMode) ? '$1' + breakTag : '$1' + breakTag + '$2';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}

const ValidationGenerate = {
    required: (val, theName) => {
        const chk = Validation_required(val, theName);
        if (chk.status == 0) {
            toastr.info(chk.messages);
            return false;
        }
        return true
    },

    moreZero: (val, theName) => {
        try {
            if (parseInt(val) <= 0) {
                toastr.info(theName + ' have to more than zero');
                return false;
            }

        } catch (err) {
            return false;
        }

        return true;
    },

    initializeProcess: (selector) => {
        let BoolProcess = true;
        selector.each(function (e) {
            const rule = ($(this).attr('rule')).split(',');
            const valueData = ($(this).val()).trim();
            const theName = $(this).attr('name');
            for (var i = 0; i < rule.length; i++) {
                const nameRule = rule[i];
                if (nameRule != '' && nameRule !== undefined) {
                    try {
                        const pr = ValidationGenerate[nameRule](valueData, theName);
                        if (!pr) {
                            BoolProcess = false;
                            return;
                        }
                    } catch (err) {
                        BoolProcess = false;
                        return;
                    }
                }
            }
        })

        if (!BoolProcess) {
            return false;
        }
        return true;
    }
}



class Class_Template {

    constructor() {
        this.data = [];
        this.obj = {};
        this.Wrhtml = '';
    }

    getHtml = () => {
        return this.Wrhtml;
    }

    getdata = () => {

        return this.data;

    }

    getobj = () => {

        return this.obj;

    }

    insertJs = (result, ...args) => {
        return result(...args);
    }

    writeHtml = (selector) => {
        selector.html(this.Wrhtml);
        return this;
    }

    loading_page = (selector) => {
        selector.html('<div class="row">' +
            '<div class="col-md-12" style="text-align: center;">' +
            '<h5 class="animated flipInX"><i class="fas fa-2x fa-sync-alt fa-spin"></i> <span>Loading page . . .</span></h5>' +
            '</div>' +
            '</div>');
    }

    loading_page_simple = (selector) => {
        var arrp = ['center', 'left', 'right'];
        var p = (typeof position !== 'undefined' && position != '' && position != null && $.inArray(position, arrp) != -1) ?
            'text-align:' + position + ';' :
            '';
        selector.html('<div style="margin-top: 1px;' + p + '">' +
            '<h5 class="animated flipInX"><i class="fas fa-2x fa-sync-alt fa-spin"></i> <span>Loading page . . .</span></h5>' + '</div>');
    }

    loading_button = (selector) => {
        selector.html('<i class="fas fa-2x fa-sync-alt fa-spin"></i> Loading...');
        selector.prop('disabled', true)
    }

    end_loading_button = (selector, html = '') => {
        selector.prop('disabled', false).html(html);
    }

    UrlExists = (url) => {
        var http = new XMLHttpRequest();
        http.open('HEAD', url, false);
        http.send();
        return http.status != 404;
    }

    AjaxSubmitFormPromisesNoToken = (url = '', data, ArrUploadFilesSelector = [], Apikey = '', requestHeader = {}) => {
        return new Promise((resolve, reject) => {
            var form_data = new FormData();
            for (const key in data) {
                form_data.append(key, data[key]);
            }

            if (ArrUploadFilesSelector.length > 0) {
                for (var i = 0; i < ArrUploadFilesSelector.length; i++) {
                    var NameField = ArrUploadFilesSelector[i].NameField + '[]';
                    var Selector = ArrUploadFilesSelector[i].Selector;
                    var UploadFile = Selector[0].files;
                    for (var count = 0; count < UploadFile.length; count++) {
                        form_data.append(NameField, UploadFile[count]);
                    }
                }
            }

            $.ajax({
                type: "POST",
                // url:url+'?apikey='+Apikey,
                url: (Apikey != '') ? url + '?apikey=' + Apikey : url,
                data: form_data,
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                dataType: "json",
                beforeSend: function (xhr) {
                    for (let key in requestHeader) {
                        xhr.setRequestHeader(key, requestHeader[key]);
                    }

                },
                success: function (data) {
                    resolve(data);
                },
                error: function (data) {
                    reject();
                }
            })
        })
    }

    AjaxSubmitFormPromises = (url = '', token = '', ArrUploadFilesSelector = [], Apikey = '', requestHeader = {}) => {
        return new Promise((resolve, reject) => {
            var form_data = new FormData();
            form_data.append('token', token);
            if (ArrUploadFilesSelector.length > 0) {
                for (var i = 0; i < ArrUploadFilesSelector.length; i++) {
                    var NameField = ArrUploadFilesSelector[i].NameField + '[]';
                    var Selector = ArrUploadFilesSelector[i].Selector;
                    var UploadFile = Selector[0].files;
                    for (var count = 0; count < UploadFile.length; count++) {
                        form_data.append(NameField, UploadFile[count]);
                    }
                }
            }

            $.ajax({
                type: "POST",
                // url:url+'?apikey='+Apikey,
                url: (Apikey != '') ? url + '?apikey=' + Apikey : url,
                data: form_data,
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                dataType: "json",
                beforeSend: function (xhr) {
                    for (let key in requestHeader) {
                        xhr.setRequestHeader(key, requestHeader[key]);
                    }

                },
                success: function (data) {
                    resolve(data);
                },
                error: function (data) {
                    reject();
                }
            })
        })
    }

    AjaxHtmlFormPromises = (url = '', token = '', ArrUploadFilesSelector = [], Apikey = '', requestHeader = {}) => {
        return new Promise((resolve, reject) => {
            var form_data = new FormData();
            form_data.append('token', token);
            if (ArrUploadFilesSelector.length > 0) {
                for (var i = 0; i < ArrUploadFilesSelector.length; i++) {
                    var NameField = ArrUploadFilesSelector[i].NameField + '[]';
                    var Selector = ArrUploadFilesSelector[i].Selector;
                    var UploadFile = Selector[0].files;
                    for (var count = 0; count < UploadFile.length; count++) {
                        form_data.append(NameField, UploadFile[count]);
                    }
                }
            }

            $.ajax({
                type: "POST",
                // url:url+'?apikey='+Apikey,
                url: (Apikey != '') ? url + '?apikey=' + Apikey : url,
                data: form_data,
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                dataType: "html",
                beforeSend: function (xhr) {
                    for (key in requestHeader) {
                        xhr.setRequestHeader(key, requestHeader[key]);
                    }

                },
                success: function (data) {
                    resolve(data);
                },
                error: function (data) {
                    reject();
                }
            })
        })
    }

    file_validation = (ev, TheName = 'Upload File', typeFile = ['pdf'], sizeFile = 5000000) => {
        var files = ev[0].files;
        var error = '';
        var msgStr = '';
        var max_upload_per_file = 4;
        if (files.length > 0) {
            if (files.length > max_upload_per_file) {
                msgStr += TheName + ' 1 Document should not be more than 4 Files<br>';

            } else {
                for (var count = 0; count < files.length; count++) {
                    var no = parseInt(count) + 1;
                    var name = files[count].name;
                    var extension = name.split('.').pop().toLowerCase();
                    if (jQuery.inArray(extension, typeFile) == -1) {
                        msgStr += 'Upload File ' + TheName + ' Invalid Type File<br>';
                    }

                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(files[count]);
                    var f = files[count];
                    var fsize = f.size || f.fileSize;

                    if (fsize > sizeFile) // 5mb
                    {
                        msgStr += TheName + ' Image File Size is very big<br>';
                    }

                }
            }
        } else {
            msgStr += TheName + ' Required';
        }
        return msgStr;
    }

    sleep = async (result, ...args) => {
        this.timeout(1000);
        return result(...args);
    }

    timeout = (ms) => {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    select2LoadDB = (selector, url, minimumInputLength = 3, placeholder = 'Search') => {
        return new Promise((resolve, reject) => {
            selector.select2({
                theme: "bootstrap",
                minimumInputLength: minimumInputLength,
                allowClear: true,
                placeholder: placeholder,
                ajax: {
                    dataType: 'json',
                    url: url,
                    delay: 250,
                    data: function (params) {
                        // console.log(params);
                        return {
                            search: (params.term === undefined) ? '' : params.term,
                        }
                    },
                    processResults: function (data, page) {
                        resolve();
                        return {
                            results: data
                        };
                    },
                    error: function (data) {
                        reject();
                    }
                }
            })
        })

    }

    select2LoadDBPost = (selector, url, token, minimumInputLength = 3, placeholder = 'Search') => {
        selector.select2({
            theme: "bootstrap",
            minimumInputLength: minimumInputLength,
            allowClear: true,
            placeholder: placeholder,
            ajax: {
                dataType: 'json',
                url: url,
                delay: 250,
                type: 'post',
                data: function (params) {
                    // console.log(params);
                    return {
                        search: (params.term === undefined) ? '' : params.term,
                        token: token,
                    }
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                },
            }
        })
    }

    stripScripts = (s) => {
        var div = document.createElement('div');
        div.innerHTML = s;
        var scripts = div.getElementsByTagName('script');
        var i = scripts.length;
        while (i--) {
            scripts[i].parentNode.removeChild(scripts[i]);
        }
        return div.innerHTML;
    }

    FormSubmitAuto = (action, method, values, blank = '_blank') => {
        var form = $('<form/>', {
            action: action,
            method: method
        });
        $.each(values, function () {
            form.append($('<input/>', {
                type: 'hidden',
                name: this.name,
                value: this.value
            }));
        });
        form.attr('target', blank);
        form.appendTo('body').submit();
    }

    /* cara penggunaan FormSubmitAuto
      var url = base_url_js+'finance/export_excel_report';
      data = {
        Data : dataa,
        summary : summary,
        PostPassing : PostPassing,
      }
      var token = jwt_encode(data,"UAP)(*");
      FormSubmitAuto(url, 'POST', [
          { name: 'token', value: token },
      ]);*/

    formatRupiah = (bilangan) => {
        var ReadMinus = function (bilangan) {
            var bool = false;
            var number_string = bilangan.toString();
            var a = number_string.substr(0, 1);
            var n = number_string.length;
            if (a == '-') {
                bool = true;
                bilangan = number_string.substr(1, n);
            }

            var dt = {
                status: bool,
                bilangan: bilangan,
            };

            return dt;
        }

        var chkminus = ReadMinus(bilangan);
        var minus = (chkminus['status']) ? '- ' : '';
        bilangan = chkminus['bilangan'];

        var number_string = bilangan.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return minus + 'Rp. ' + rupiah + ',-';
    }

    replace_obj_value_null = (data, replace = '-') => {
        for (let key in data) {
            if (data[key] == null || data[key] == 'null' || data[key] === undefined) {
                data[key] = replace;
            }
        }

        return data;
    }

    loadingStart = () => {
        $('#modalLoading .modal-content').html('<center style = "margin-top:5px;margin-bottom:5px;">' +
            '                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i> &nbsp' +
            '                    	' +
            '                    loading data . . .' +
            '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-secondary invisible">Close</button>' +
            '                </center>');
        $('#modalLoading').modal({
            'backdrop': 'static',
            'show': true
        });
    }

    loadingStartWithProgress = () => {
        $('#modalLoading .modal-content').html('<center style = "margin-top:5px;margin-bottom:5px;">' +
            '                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i> &nbsp' +
            '                    	' +
            '                    loading data (<span id="dataLoadingStartWithProgress">0</span>%)' +
            '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-secondary invisible">Close</button>' +
            '                </center>');
        $('#modalLoading').modal({
            'backdrop': 'static',
            'show': true
        });
    }

    loadingEnd = (timeout = 1000) => {
        setTimeout(function () {
            $('#modalLoading').modal('hide');
        }, timeout);

    }

    response_form = (data) => {
        try {
            data = JSON.parse(data);
            if (data.status == 'error') {
                toastr.error(data.message);
            } else if (data.redirect) {
                window.location = data.redirect;
            } else if (data.status == 'success') {
                toastr.success(data.message);
            }
        } catch (err) {
            toastr.info(err + ' : something wrong, please contact administrator')
        }
    }

    response_form_token = (data) => {
        try {
            if (data.status == 'error') {
                toastr.error(data.message);
            } else if (data.redirect) {
                window.location = data.redirect;
            } else if (data.status == 'success') {
                toastr.success(data.message);
            }
        } catch (err) {
            toastr.info(err + ' : something wrong, please contact administrator')
        }
    }

    tableStandard = (cnfg) => {
        /*
        config ex :
            let cnfg = {
                headerData : json_data.headerTable,
                valueData : json_data.valueTable,
                selector : $('.pageTable'),
                datatable : false,
                fieldValueTBL : true,

            }

        */

        let [headerData, valueData, selector, datatable, fieldValueTBL] = [cnfg.headerData, cnfg.valueData, cnfg.selector, cnfg.datatable, cnfg.fieldValueTBL]

        let html = '<table class = "table">' +
            '<thead>' +
            '<tr>';

        for (var i = 0; i < headerData.length; i++) {
            html += '<th>' + headerData[i] + '</th>';
        }

        html += '</tr>';
        html += '</thead>';

        html += '<tbody>';

        for (var i = 0; i < valueData.length; i++) {
            html += '<tr>';
            if (fieldValueTBL) { // required field value_table
                let arrV = valueData[i].value_table;
                for (var j = 0; j < arrV.length; j++) {
                    html += '<td>' + arrV[j] + '</td>';
                }
            } else {
                for (key in valueData[i]) {
                    html += '<td>' + valueData[i][key] + '</td>';
                }
            }

            html += '</tr>';
        }

        html += '</tbody>';
        html += '</table>';

        selector.html(html);

        if (datatable) {
            selector.find('.table').DataTable();
        }


    }

    MakeAutoNumbering = (selector) => {
        var no = 1;
        // $('.content-option').find(".table tbody tr").each(function(){
        selector.each(function () {
            var a = $(this);
            a.find('td:eq(0)').html(no);
            no++;
        })
    }

    createUniqueChar = () => {
        var n = Math.floor(Math.random() * 11);
        var k = Math.floor(Math.random() * 1000000);
        var m = String.fromCharCode(n) + k;

        return m;
    }
}

const saveTable2Excel = (element) => {
    var elm = $('.' + element);
    var name = elm.attr('data-name');
    var preserveColors = (elm.hasClass('table2excel_with_colors') ? true : false);
    elm.table2excel({
        exclude: ".noExl",
        filename: name,
        fileext: ".xlsx",
        preserveColors: preserveColors
    });
}

function ucwords(str) {
    // var str = "hello world";
    str = str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
}

function unsetfilter(filter_name) {
    // let session_name = $(this).attr('data-name');
    if (confirm('Reset filter ?')) {
        let url = base_url + 'unsetsession/' + filter_name;
        $.get(url, function (result) {
            // console.log(result);
            window.location.href = "";
        })
    }
}

// default template
if ('ontouchstart' in document.documentElement) document.write("<script src='" + base_url + "assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");

var btnPasteHere = function (context) {
    var ui = $.summernote.ui;

    // create button
    var button = ui.button({
        contents: '<i class="fa fa-clipboard"/> Paste text',
        tooltip: 'Paste text',
        click: function () {
            // invoke insertText method with 'hello' on editor module.

            $('#ModalForm .modal-header').removeClass('hide');
            $('#ModalForm .modal-header .modal-title').html('Paste text');
            $('#ModalForm .modal-footer').addClass('hide');
            $('#ModalForm .modal-dialog').removeClass('modal-sm modal-lg');

            $('#ModalForm .modal-body').html('<div class="row"><div class="col-md-12">' +
                '<textarea id="fillModalPaste" class="form-control" rows="10" placeholder="Paste here..."></textarea>' +
                '<div style="text-align: right;float: right;">' +
                '<hr/>' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
                ' | <button type="button" class="btn btn-success" id="btnSaveModalPaste">Save</button> ' +
                '</div></div></div>');


            $('#ModalForm').on('shown.bs.modal', function () {
                $('#fillModalPaste').focus()
            });

            $('#ModalForm').modal({
                'backdrop': 'static',
                'show': true
            });

            $('#btnSaveModalPaste').click(function () {
                var fillModalPaste = $('#fillModalPaste').val();
                context.invoke('editor.insertText', fillModalPaste);
                $('#ModalForm').modal('hide');
            });
        }
    });

    return button.render(); // return button as jquery object
};

// summernote
function summernote_UploadImage_selector(selector, image, SummernoteID) {
    var data = new FormData();
    data.append("image", image);
    $.ajax({
        // url: "<?= base_url('c_summernote/upload_image?id=')?>"+SummernoteID,
        url: base_url + 'c_summernote/upload_image?id=' + SummernoteID,
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        success: function (url) {
            selector.summernote("insertImage", url);
        },
        error: function (data) {
            alert('Error Upload');
            console.log(data);
        }
    });
}

function summernote_DeleteImage(src) {
    $.ajax({
        data: { src: src },
        type: "POST",
        //url: "<?= base_url('upload/summernote/delete_image')?>",
        url: base_url + 'c_summernote/delete_image',
        cache: false,
        success: function (response) {
            console.log(response);
        }
    });
}

// end summernote

const __modal_table_config = () => {
    modal_table_config = {
        responsive: {
            details: {
                type: 'column'
            }
        },
        buttons: [{
            extend: 'colvis',
            text: '<i class="icon-grid3"></i>',
            className: 'btn btn-sm btn-link dropdown-toggle p-0',
            columns: ':not(.novis)'
        }],
        //    stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#ModalFormLarge').find('.modal-body').find('#table_default').attr('data-url'),
            type: 'post',
            data: function (d) {
                $('#ModalFormLarge').find('.modal-body').find('.filterSearch select[tabindex="-1"], .filterSearch input').each(function () {
                    filterTableModal[$(this).attr("name")] = $(this).val();
                });
                d.filter = filterTableModal;
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
            [$('#ModalFormLarge').find('.modal-body').find('th.default-sort').index(), $('#ModalFormLarge').find('.modal-body').find('th.default-sort').attr('data-sort')]
        ],
        orderCellsTop: true,
        'createdRow': function (row, data, dataIndex) {

        },
        "initComplete": function (settings, json) {

        },
    };

}

const __modal_table_config2 = (selectorModal) => {
    modal_table_config = {
        responsive: {
            details: {
                type: 'column'
            }
        },
        buttons: [{
            extend: 'colvis',
            text: '<i class="icon-grid3"></i>',
            className: 'btn btn-sm btn-link dropdown-toggle p-0',
            columns: ':not(.novis)'
        }],
        //    stateSave: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: selectorModal.find('.modal-body').find('#table_default').attr('data-url'),
            type: 'post',
            data: function (d) {
                selectorModal.find('.modal-body').find('.filterSearch select[tabindex="-1"], .filterSearch input').each(function () {
                    filterTableModal[$(this).attr("name")] = $(this).val();
                });
                d.filter = filterTableModal;
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
            [selectorModal.find('.modal-body').find('th.default-sort').index(), selectorModal.find('.modal-body').find('th.default-sort').attr('data-sort')]
        ],
        orderCellsTop: true,
        'createdRow': function (row, data, dataIndex) {

        },
        "initComplete": function (settings, json) {

        },
    };

}

const __replaceDecimal = (x) => {
    x = x.toString().replace('.', ",");
    console.log(x);
    return x;
}

const App_template = new Class_Template();