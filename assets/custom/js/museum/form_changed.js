$(document).ready(function () {
    console.log(dataModule);
    console.log(dataModule.data.data_history);
    loadData();
})

function loadData() {
    let data_history = JSON.parse(dataModule.data.data_history);

    $('#tanggal').html(' - ' + moment(dataModule.data.created_at).format('dddd, DD MMM YYYY HH:mm'));

    console.log(data_history);
    let tr = '';
    $.each(data_history, function (i, v) {

        let style_changed = (v.is_changed == 1) ? ' style="background: #ecf7ff;"' : '';

        let new_tr = `<tr>
        <th  style="background: #f4f4f4;">${v.label}</th>
        <td>${v.value_before}</td>
        <td ${style_changed}>${v.value_after}</td>
        </tr>`;

        if (v.id == "div_atribut") {
            let vb_tr = '';
            $.each(v.value_before, function (ib, vb) {
                let b_tr = `<tr>
                <td style="width:25%;">${vb.left}</td>
                <td style="width:5%;">:</td>
                <td style="width:15%;">${vb.attribute_group_detail_value}</td>
                <td>${vb.right}</td>
                </tr>`;

                vb_tr = vb_tr + b_tr;
            });

            let pnl_before = (vb_tr != '') ? `<table class="table table-atr">${vb_tr}</table>` : '';

            let va_tr = '';
            $.each(v.value_after, function (ia, va) {
                let a_tr = `<tr>
                <td style="width:25%;">${va.left}</td>
                <td style="width:5%;">:</td>
                <td style="width:15%;">${va.attribute_group_detail_value}</td>
                <td>${va.right}</td>
                </tr>`;

                va_tr = va_tr + a_tr;
            });

            let pnl_after = (va_tr != '') ? `<table class="table table-atr">${va_tr}</table>` : '';



            new_tr = `<tr>
                <th  style="background: #f4f4f4;">${v.label}</th>
                <td>${pnl_before}</td>
                <td>${pnl_after}</td>
                </tr>`;
        }



        tr = tr + new_tr;
    });

    $('#viewTable').html(`<table class="table table-bordered">
    <thead>
        <tr style="text-align:center;background: #f4f4f4;">
            <th style="text-align:center;width:20%;">Atribut</th>
            <th style="text-align:center;width:40%;">Sebelum</th>
            <th style="text-align:center;width:40%;">Sesudah</th>
        </tr>
    </thead>
    <tbody>${tr}</tbody>
    </table>`);
}