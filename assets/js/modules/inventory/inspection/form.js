console.log(dataForm)
console.log(dataModule)
let oTableModal;

Vue.component('model', {
    props: ['title', 'ref_id'],
    template: modal_component,
});


const app = new Vue({
    el: '#app',
    data: {
        form: {
            material_receipt_id: '',
        },
        show: {
            purchase_order_number: '',
            item_code: '',
            item_name: '',
            quantity: '',
            supplier_name: '',
        },
        modal: {
            title: 'Choose Material Receipt',
            ref_id: 'myModal',
        },
        showModal: false
    },
    updated() {

    },
    beforeCreate() {
        App_template.loadingStart();
    },
    created() {

    },
    mounted() {
        console.log(dataForm);
        if (dataForm['material_receipt_id'] !== undefined) {
            this.form = dataForm;
            this.load_edit_data();
        }

        App_template.loadingEnd(500);
    },
    methods: {
        load_edit_data() {
            this.show = {
                purchase_order_number: this.form.material_receipt.purchase_order_number,
                item_code: this.form.material_receipt.item.item_code,
                item_name: this.form.material_receipt.item.name,
                quantity: this.form.material_receipt.quantity,
                supplier_name: this.form.material_receipt.supplier.supplier_name,
            };
        },
        async get_select2_employees(selector) {
            const url = base_url + 'administration/user_management/users/select2_employees';
            const selected_id = selector.find('option:selected').val();
            const selected_id_text = selector.find('option:selected').text();
            const placeholder = {
                id: selected_id, // the value of the option
                text: selected_id_text
            }

            try {
                const response = await App_template.select2LoadDB(selector, url, 3, placeholder);
            } catch (err) {
                console.log(err);
            }
        },
        async onClickfindMaterialReceipt(event) {
            let htmlBtn = event.target.innerHTML;
            App_template.loading_button($(event.target));

            this.showModal = true;
            await App_template.timeout(500);
            $('#' + this.modal.ref_id).modal({
                'show': true,
                'backdrop': 'static'
            });
            await App_template.timeout(500);
            if ($.fn.dataTable.isDataTable('#table_default')) {
                oTableModal.ajax.reload((e) => {
                    // event function
                });
            } else {
                const selectorModal = $('#' + this.modal.ref_id);
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
        onClickSetShow(data) {
            this.form.material_receipt_id = data.material_receipt_id;
            console.log(data);
            this.show = {
                purchase_order_number: data['purchase_order_number'],
                item_code: data['item_code'],
                quantity: data['quantity'],
                supplier_name: data['supplier_name'],
                item_name: data['item_name'],
            };
        },
        // async onSubmitData() {
        //     let data = $('#form').serializeArray();
        //     const url = $(form).attr('action');
        //     const PostData = data;
        //     console.log(PostData);
        // }
    }

});

$(document).ready(function(e) {
    const sel1 = $('#received_by');
    const sel2 = $('#known_by');
    app.get_select2_employees(sel1);
    app.get_select2_employees(sel2);
})


$(document).on('click', '.btnChooseMaterialReceipt', function(e) {
    const t = $(this).attr('data-token');
    const item = jwt_decode(t);
    app.onClickSetShow(item);
})