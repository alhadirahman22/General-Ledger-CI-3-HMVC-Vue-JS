$(document).ready(function () {
    // $.ajax({
    //     dataType: "json",
    //     url: "http://localhost:3000/rimau/assets/orgchart/sample.json"
    // }).done(function (data) {
    //     OrgTree.makeOrgTree($('#tree'), data);
    // });


    $.ajax({
        dataType: "json",
        url: "http://localhost:3000/rimau/assets/orgchart/sample.json"
    }).done(function (data) {

        console.log(data);

        OrgTree.setOptions({
            baseClass: "org-tree",
            baseLevel: 12,
            minWidth: 2,
            collapsable: true,
            renderNode: function (node) {
                return `<div class="node center-block text-center">
                              <strong>${node.name}</strong><br />
                              <em>${node.label}</em>
                          </div>`
            },
            renderCollapseIcon: function (node) {
                if (this.collapsable && node.children && node.children.length > 0) {
                    return `<br />
                    <a href="#" class="collapse_node">
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
        OrgTree.makeOrgTree($('#tree'), data);
    });

});