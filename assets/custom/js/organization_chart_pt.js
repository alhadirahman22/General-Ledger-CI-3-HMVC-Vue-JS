$(document).ready(function () {
    $('#pt').select2();
    $('#emp_pt').select2();
    console.log(dataModule);
    generatePTChart();
});



function generatePTChart() {
    // OrgTree.makeOrgTree($('#tree'), dataModule.data);
}

$('#pt').change(function () {
    let pt_id = $('#pt').val();

    if (pt_id != '' && pt_id != null && pt_id != 'null') {
        $('#tree').empty();
        let url = dataModule.module_url + 'getchartpt?pt_id=' + pt_id;
        $.getJSON(url, function (resultJson) {
            console.log(resultJson);
            OrgTree.makeOrgTree($('#tree'), resultJson);
        });
    }

});

// ======================

$('#emp_pt').change(function () {
    let pt_id = $('#emp_pt').val();
    if (pt_id != '' && pt_id != null && pt_id != 'null') {

        $('#tree').empty();
        let url = dataModule.module_url + 'getchartpt?pt_id=' + pt_id;
        $.getJSON(url, function (resultJson) {
            console.log(resultJson);

            OrgTree.setOptions({
                baseClass: "org-tree",
                baseLevel: 12,
                minWidth: 2,
                collapsable: true,
                renderNode: function (resultJson) {

                    console.log(resultJson);

                    let cls = (resultJson.type == 1) ? '' : 'bg-gray';
                    let node = (resultJson.type == 1) ? 'node' : 'node-2';

                    let show_content = (resultJson.type == 1) 
                    ? `<div class="text-center"> ${resultJson.label} </div>` 
                    : `<div class="text-center"> 
                        <div style="background:#438eb9;color:#fff;">
                        ${resultJson.label}
                        </div>
                        <div>Nandang Mulyadi</div>
                    </div>` ;

                    return `<div class="${node} center-block ${cls}">
                            ${show_content}
                            ${this.renderCollapseIcon(resultJson)}
                        </div>`;
                },
                renderCollapseIcon: function (node) {
                    if (this.collapsable && node.children && node.children.length > 0) {
                        return `<a href="#" class="collapse_node">
                            <i class="glyphicon glyphicon-minus-sign"></i>
                        </a>`;
                    } else {
                        return '';
                    }
                },
                toggleCollapseIcon: function (icon) {
                    $(icon).toggleClass('glyphicon-minus-sign glyphicon-plus-sign');
                }
            });



            OrgTree.makeOrgTree($('#tree'), resultJson);
        });

    }
});