console.log(dataModule);
console.log(dataForm);
console.log(data_unit);
console.log(dataItem);
// App_template.loadingStart();

Vue.component('model', {
    props: ['title', 'ref_id'],
    template: modal_component,
});

const app = new Vue({
    el: '#app',
    data: {
        form: dataForm,
        warehouseItem: dataItem,
        deleted: [],
        modal: {
            title: 'Form Reject',
            ref_id: 'myModal',
            item_user_request_detail_id: '',
        },
        dataModal: '',
        auth: dataModule['auth'],
    },
    updated() {
        $('.selectWarehouseID:last').select2({
            allowClear: true,
        })
    },
    beforeCreate() {
        App_template.loadingStart();
    },
    created() {
        App_template.loadingEnd(500);
    },
    mounted() {

    },
    methods: {
        Add_Dom_Item() {
            if (this.warehouseItem.length > 0) {
                this.form.detail.push({
                    item_user_request_detail_id: null,
                    item_user_request_id: null,
                    warehouse_id: null,
                    quantity: null,
                    // unit_id: null,
                    description: null,
                    // unit: [],
                    warehouse: {
                        item: {
                            unit: {}
                        },
                    },
                });
            } else {
                toastr.info('No item from this department');
            }
        },
        Delete_Dom_Item(keyIndex) {
            const d = this.form.detail.find((x, i) => i === keyIndex);
            this.deleted.push(d); // for delete in table
            this.form.detail = this.form.detail.filter((x, i) => i !== keyIndex);
        },
        async onSubmitData() {
            console.log(this.form);
            const url = $(form).attr('action');
            // const PostData = this.form;
            const PostData = {
                data: this.form,
                deleted: this.deleted,
            }
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
        },
        onChangeItem(warehouse_id, indexKey) {
            this.form['detail'][indexKey]['warehouse_id'] = warehouse_id;
            const d = this.warehouseItem.find(x => parseInt(x.warehouse_id) === parseInt(warehouse_id));
            this.form['detail'][indexKey]['warehouse'] = (d) ? d : {
                item: {
                    unit: {}
                },
            };

        },
        async approve(item_user_request_detail_id) {
            // console.log(item_user_request_detail_id);
            // return;
            if (confirm('Are you sure ?')) {
                const url = dataModule['module_url'] + 'approve';
                const PostData = {
                    item_user_request_detail_id: item_user_request_detail_id,

                }
                const token = jwt_encode(PostData, jwtKey);
                App_template.loadingStart();
                try {
                    const json = await App_template.AjaxSubmitFormPromises(url, token);
                    if (json.status == 1 || json.status == '1') {
                        // this.form.detail.forEach(x => {
                        //     if (parseInt(x.verify_status) === parseInt(item_user_request_detail_id)) {
                        //         x.verify_status = '1';
                        //         x.verify_by = dataModule['user']['id'];
                        //     }
                        // });
                        this.form = json.callback.form;
                        this.warehouseItem = json.callback.dataItem;
                        toastr.success('Saved');
                    } else {
                        toastr.info(json.msg);
                        this.form = json.callback.form;
                    }
                    App_template.loadingEnd(0);
                } catch (err) {
                    console.log(err);
                    App_template.loadingEnd(0);
                }
            }

        },
        reject(item_user_request_detail_id) {
            this.modal.item_user_request_detail_id = item_user_request_detail_id;
            $('#' + this.modal.ref_id).modal({
                'show': true,
                'backdrop': 'static'
            });

        },
        async onSubmitReject(event) {
            let htmlBtn = event.target.innerHTML;
            App_template.loading_button($(event.target));
            const url = dataModule['module_url'] + 'reject';
            const PostData = {
                item_user_request_detail_id: this.modal.item_user_request_detail_id,
                verify_reason: this.dataModal
            }
            const token = jwt_encode(PostData, jwtKey);
            try {
                const json = await App_template.AjaxSubmitFormPromises(url, token);
                if (json.status == 1 || json.status == '1') {
                    // this.form.detail.forEach(x => {
                    //     if (parseInt(x.verify_status) === parseInt(item_user_request_detail_id)) {
                    //         x.verify_status = '-1';
                    //         x.verify_by = dataModule['user']['id'];
                    //     }
                    // });
                    this.form = json.callback.form;
                    this.warehouseItem = json.callback.dataItem;
                    toastr.success('Saved');
                    $('#' + this.modal.ref_id).modal('hide');
                } else {
                    toastr.info(json.msg);
                    this.form = json.callback.form;
                    this.warehouseItem = json.callback.dataItem;
                }
                App_template.end_loading_button($(event.target), htmlBtn);
            } catch (err) {
                console.log(err);
                App_template.end_loading_button($(event.target), htmlBtn);
            }
        },
    }

});

$(document).ready(function(e) {
    $('.selectWarehouseID').select2({
        // allowClear: true,
    })
})

$(document).on('change', '.selectWarehouseID', function(e) {
    const v = $(this).find('option:selected').val();
    const indexKey = parseInt($(this).attr('index_key'));
    app.onChangeItem(v, indexKey);
})