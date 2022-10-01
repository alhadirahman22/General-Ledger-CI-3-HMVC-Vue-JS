$(document).ready(function () {
    console.log(dataModule);

    loadhistorycity();

});

function loadhistorycity() {


    if (dataModule.leave_hitories.length > 0) {

        let tr = '';
        $.each(dataModule.leave_hitories, function (i, v) {
            // console.log(v);

            let desc = (v.leave_id != 0)
                ? v.leave_category_name : 'Penyesuaian By HR Staff';

            let ins = moment(v.insert_at).format('DD MMM YYYY HH:mm');

            let pembatalan = (v.leave_id != 0 && v.type == 1) ? '[<span class="orange">PEMBATALAN</span>] - ' : '';

            let value_cuti = (v.type == 1)
                ? '<div class="green"><b style="text-align:left;">+</b> <b style="float:right;"> ' + v.value + '</b></div>'
                : '<div class="orange"><b style="text-align:left;">-</b> <b style="float:right;"> ' + v.value + '</b></div>';

            let view_date = (v.leave_id != 0)
                ? `<div style="font-size:10px;color:#7c7c7c;">${moment(v.start_date).format('DD MMM YYYY HH:mm')} - ${moment(v.end_date).format('DD MMM YYYY HH:mm')}</div>` : '';

            let new_tr = `<tr>
            <td style="text-align:center;">${i + 1}</td>
            <td>${pembatalan}${desc}${view_date}</td>
            <td >${value_cuti}</td>
            <td style="text-align:right;">${ins}</td>
            </tr>`;

            tr = tr + new_tr;
        });

        $('#cuti').parent().parent().append(`<div class="col-md-7">
           <div class="well">
                <div class="table-responsive">
                <h4>Riwayat Cuti</h4>
                <table class="table table-bordered table-striped" style="margin-bottom:0px;">
                    <thead>
                        <tr style="background:#ededed;">
                            <th style="width:3%;text-align:center;">No</th>
                            <th style="text-align:center;">Description</th>
                            <th style="width:7%;text-align:center;">Value</th>
                            <th style="width:25%;text-align:center;">Insert At</th>
                        </tr>
                    </thead>
                    <tbody>${tr}</tbody>
                </table>
            </div>   
           </div>
            </div>`);

    }


}