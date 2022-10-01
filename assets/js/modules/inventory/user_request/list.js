console.log(dataModule);

const reload_session = async(warehouse_sub_id) => {
    const url = dataModule.module_url + 'change_warehouse_sub';
    const PostData = {
        warehouse_sub_id: warehouse_sub_id,
    }
    const token = jwt_encode(PostData, jwtKey);
    App_template.loadingStart();
    try {
        const json = await App_template.AjaxSubmitFormPromises(url, token);
        // $('#table_default').DataTable()
        let tbl = template_table_default.param.sel_table;
        tbl.ajax.reload((e) => {

        });
        App_template.loadingEnd(500);
    } catch (err) {
        console.log(err);
        App_template.loadingEnd(0);
    }
};

$(document).on('click', '.warehouse_sub', function(e) {
    const warehouse_sub_id = $(this).attr('key');
    reload_session(warehouse_sub_id);
    $('.warehouse_sub').closest('.nav-tabs').find('.warehouse_subNav').removeClass('active');
    $(this).closest('.warehouse_subNav').addClass('active');

})