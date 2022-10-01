console.log(dataModule);
console.log(Itemcategory);
let oTableVue;


const ItemCategory = {
    template: `
                <div>
                    ` + htmlTable + `
                   <!--<div> <h2>{{ id}}</h2></div>-->
                </div>
              `,
    // template: '<div>ID: {{ id}}</div>',
    props: ['id'],
    mounted() {
        this.getData();
    },
    created() {
        // console.log(this.id);
    },
    watch: {
        $route: "getData",
    },
    methods: {
        async getData() {
            const itsmeVue = this;
            await this.setSessionCategory();
            // console.log(oTableVue);
            // console.log(this);
            const vue_link_table_config = {
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
                    url: $('#table_default2').attr('data-url'),
                    type: 'post',
                    data: function(d) {
                        $('#table_default2').find('.filterSearch select[tabindex="-1"], .filterSearch input').each(function() {
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
                    [$('#table_default2').find('th.default-sort').index(), $('#table_default2').find('th.default-sort').attr('data-sort')]
                ],
                orderCellsTop: true,
                'createdRow': function(row, data, dataIndex) {

                },
                "initComplete": function(settings, json) {

                },
            };
            if ($.fn.dataTable.isDataTable('#table_default2')) {
                oTableVue.ajax.reload((e) => {
                    // event function
                });
            } else {
                var table = $('#table_default2').DataTable(vue_link_table_config);
                oTableVue = table;

                $('.buttons-colvis').detach().appendTo('#action');
                $('body').on('keyup', '.filterSearch input', function(e) {
                    if (e.keyCode == 13) {
                        oTableVue.ajax.reload((e) => {
                            // event function
                        });
                    }
                });

                $('body').on('change', '.filterSearch select', function(e) {
                    oTableVue.ajax.reload((e) => {

                    });
                });

                $('body').on('click', '.delete_row_custom', function(e) {

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
                                    itsmeVue.delete_row_default(get_url)
                                },
                            },
                        }
                    });
                });
            }

        },

        async setSessionCategory() {
            const url = dataModule['module_url'] + 'setSessionCategory';
            const data = {
                item_category_id_selected: this.id
            };
            const token = jwt_encode(data, jwtKey);
            const json = await App_template.AjaxSubmitFormPromises(url, token);
        },
        async delete_row_default(get_url) {
            try {
                var response = await App_template.AjaxSubmitFormPromises(get_url, '');
                if (response.status == 'success') {
                    oTableVue.ajax.reload((e) => {

                    });
                    success_message(response.message);
                } else {
                    error_message(response.message);
                }

            } catch (err) {
                console.log(err);
                toastr.error('something wrong, please contact IT', '!Error');
            }
        },

    },

}


const routes = [{
        path: pathUrl + "/inventory/inspection/",
        redirect: pathUrl + "/inventory/inspection/" + Itemcategory[0]['item_category_id'],
    },
    {
        // path: this.$router.currentRoute.value.fullPath,
        path: pathUrl + '/inventory/inspection/:id?',
        // path: '*',
        component: ItemCategory,
        name: 'ItemCategory',
        props: true,
    },
];

const router = new VueRouter({
    linkActiveClass: "active",
    mode: 'history',
    routes: routes,
});

const app = new Vue({
    el: '#app',
    data: {
        title: '',
        item_category_id: '',
    },
    router: router,
    methods: {
        activeClass: function(...names) {
            for (let name of names) {
                if (name == this.$route.name)
                    return 'active';
            }
        },

    }
});