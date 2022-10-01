$(document).ready(function () {

    loadForm()
});

function loadForm() {
    console.log(dataModule);

    $.each(dataModule.form.form, function (i, v) {

        if (v.type != "hidden") {
            // get parent
            let p = $('#' + v.id).parent().parent();
            console.log(v.id, p);

            // var element = $('#' + v.id).detach();
            // p.append(element);
            // $('#' + v.id).parent().remove();
        }
    })
}