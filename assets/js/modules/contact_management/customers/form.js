// console.log(dataFormAddress);
// console.log(dataForm)
// console.log(dataModule);
if (typeof dataForm['terms_name'] === undefined) {
    dataForm['terms_name'] = '';
    dataForm['term'] = null;
    dataForm['currency_data'] = [];
    dataForm['currency'] = null;
}


Vue.component('model', {
    props: ['title', 'ref_id'],
    template: modal_component,
});

Vue.component('currency_component', {
    props: ['title', 'ref_id'],
    template: modal_component,
});


const app = new Vue({
    el: '#app',
    data: {
        form: {
            address: dataFormAddress,
            term: dataForm['term'],
            currency: dataForm['currency'],
        },
        modal: {
            terms: [],
            title: 'Choose Terms',
            ref_id_terms: 'myModal',
            ref_id_currency: 'myModal2',
            title_currency: 'Choose Currency',
            //currency : [],
        },
        show: {
            terms_name: dataForm['terms_name'],
            currency_data: dataForm['currency_data'],
        },
        showModalCurrency: false
    },

    methods: {
        async onClickfindTerms(event) {
            console.log(event);
            // console.log(this.$refs);
            // console.log($('.tbltest'));
            let htmlBtn = event.target.innerHTML;
            App_template.loading_button($(event.target));
            let html = '';
            const url = dataModule['module_url'] + 'get_terms';
            const json = await App_template.AjaxSubmitFormPromises(url);
            this.modal.terms = json;

            await App_template.timeout(500);
            $('#' + this.modal.ref_id_terms).find('table').DataTable();
            $('#' + this.modal.ref_id_terms).modal({
                'show': true,
                'backdrop': 'static'
            });

            App_template.end_loading_button($(event.target), htmlBtn);

        },
        onClickChoseTerms(item) {
            //console.log(item);
            this.show.terms_name = item.name;
            this.form.term = item.terms_id;
        },
        async onClickfindCurrency(event) {
            let htmlBtn = event.target.innerHTML;
            App_template.loading_button($(event.target));

            this.showModalCurrency = true;
            await App_template.timeout(500);
            $('#' + this.modal.ref_id_currency).modal({
                'show': true,
                'backdrop': 'static'
            });
            await App_template.timeout(500);
            if ($.fn.dataTable.isDataTable('#table_default')) {
                oTableModal.ajax.reload((e) => {
                    // event function
                });
            } else {
                const selectorModal = $('#' + this.modal.ref_id_currency);
                var columns = [];
                __modal_table_config2(selectorModal);
                selectorModal.find('.column th').each(function() {
                    columns.push({
                        data: $(this).attr('data-data')
                    });
                });

                modal_table_config.columns = columns;
                var table = selectorModal.find('#table_default').DataTable(modal_table_config);
                oTableModal = table;

                selectorModal.find('.buttons-colvis').detach().appendTo('#action');
                selectorModal.on('keyup', '.filterSearch input', function(e) {
                    if (e.keyCode == 13) {
                        oTableModal.ajax.reload((e) => {
                            // event function
                        });
                    }
                });

                selectorModal.on('change', '.filterSearch select', function(e) {
                    oTableModal.ajax.reload((e) => {

                    });
                });
            }

            App_template.end_loading_button($(event.target), htmlBtn);

        },

        onClickSelectCurrency(item) {
            this.form.currency = item.currency_id;
            this.show.currency_data = {
                entity: item.entity,
                alphacode: item.alphacode
            };
        },

        async onSubmitData() {
            let data = $('#form').serializeArray();
            const url = $(form).attr('action');
            const PostData = {
                customer: data,
                address: this.form.address,
            };

            const token = jwt_encode(PostData, jwtKey);
            if (confirm('Are you sure ?')) {
                App_template.loadingStart();
                try {
                    const json = await App_template.AjaxSubmitFormPromises(url, token);
                    App_template.response_form_token(json);
                } catch (err) {
                    console.log(err);
                } finally {
                    await App_template.timeout(1000);
                    App_template.loadingEnd(0);
                }
            }
        }
    }

});

$(document).ready(function(e) {
    // $('#credit_limit').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
    // $('#credit_limit').maskMoney('mask', '9894');
    // console.log(app)
})

$(document).on('click', '.btnChooseCurrency', function(e) {
    const t = $(this).attr('data-token');
    const item = jwt_decode(t);
    app.onClickSelectCurrency(item);
})