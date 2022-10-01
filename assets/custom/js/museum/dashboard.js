console.log(dataParser);

const bendaIsGoodPercent = () => {
    return parseFloat(dataParser.benda_is_good / dataParser.benda_is_all * 100).toFixed(6);
};

const bendaIsBadPercent = () => {
    return parseFloat(dataParser.benda_is_bad / dataParser.benda_is_all * 100).toFixed(6);
};

const bendaLast3MonthPercent = () => {
    return parseFloat(dataParser.benda_last3month / dataParser.benda_is_all * 100).toFixed(6);
};

const pengunjungPercent = () => {
    return parseFloat(dataParser.pengunjung / dataParser.all_user * 100).toFixed(2);
};

const memoryPercent = () => {
    return parseFloat(dataParser.ram_used / dataParser.ram_total * 100).toFixed(0);
}


$(document).ready(function (e) {
    $('.bendaIsGood').html(dataParser.benda_is_good);
    $('.bendaIsGoodPercent').html(bendaIsGoodPercent() + '%');
    $('.bendaIsBad').html(dataParser.benda_is_bad);
    $('.bendaIsBadPercent').html(bendaIsBadPercent() + '%');
    $('.bendaLast3Month').html(dataParser.benda_last3month);
    $('.bendaLast3MonthPercent').html(bendaLast3MonthPercent() + '%');
    $('.MutasiProcess').html(dataParser.mutasi_process)
    $('.pengunjung').html(dataParser.pengunjung);
    $('.pengunjungPercent').html(pengunjungPercent() + '%');
    $('.memoryPercent').html(memoryPercent());

    const benda_category = dataParser.benda_category;
    const benda_categorySort = benda_category.sort((a, b) => b.total - a.total);
    const htmlBendaCat = benda_categorySort.map((x) => {
        return `<tr>
                    <td> `+ x.kategori.name + `</td>
                    <td> `+ x.total + `</td>

                </tr>
        `

    }).join('')

    $('.benda-category').html(htmlBendaCat);

    const benda_department = dataParser.benda_department;
    const benda_departmentSort = benda_department.sort((a, b) => b.total - a.total);
    const htmlBendaDep = benda_departmentSort.map((x) => {
        return `<tr>
                    <td> `+ x.department.museum.name + ' - ' + x.department.name + `</td>
                    <td> `+ x.total + `</td>

                </tr>
        `

    }).join('')

    $('.benda-dept').html(htmlBendaDep);


    const benda_tagging = dataParser.benda_tagging;
    const benda_taggingSort = benda_tagging.sort((a, b) => b.total - a.total);
    const htmlBendaTag = benda_taggingSort.map((x) => {
        return `<tr>
                    <td> `+ x.tagging.name + `</td>
                    <td> `+ x.total + `</td>

                </tr>
        `

    }).join('')

    $('.benda-tagging').html(htmlBendaTag);

    $('.contentDashboard').removeClass('hide');
})


$('.easy-pie-chart.percentage').each(function () {
    var $box = $(this).closest('.infobox');
    var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
    var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
    var size = parseInt($(this).data('size')) || 50;
    console.log(size);
    $(this).easyPieChart({
        barColor: barColor,
        trackColor: trackColor,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: parseInt(size / 10),
        animate: ace.vars['old_ie'] ? false : 1000,
        size: size
    });
})

$('.sparkline').each(function () {
    var $box = $(this).closest('.infobox');
    var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
    $(this).sparkline('html',
        {
            tagValuesAttribute: 'data-values',
            type: 'bar',
            barColor: barColor,
            chartRangeMin: $(this).data('min') || 0
        });
});

// const ctx = document.getElementById('myChart').getContext('2d');

// const data = {
//     labels: [
//         'Red',
//         'Blue',
//         'Yellow'
//     ],
//     datasets: [{
//         label: 'My First Dataset',
//         data: [300, 50, 100],
//         backgroundColor: [
//             'rgb(255, 99, 132)',
//             'rgb(54, 162, 235)',
//             'rgb(255, 205, 86)'
//         ],
//         hoverOffset: 4
//     }]
// };

// const myChart = new Chart(ctx, {
//     type: 'doughnut',
//     data: data,
// });







const ctx2 = document.getElementById('chartKoleksi').getContext('2d');
const bendaCategory = dataParser.benda_category;
let newLabel = [];
let dataTotal = [];
for (let index = 0; index < bendaCategory.length; index++) {
    newLabel = [...newLabel, bendaCategory[index].kategori.name]
    dataTotal = [...dataTotal, bendaCategory[index].total]

}



const data2 = {
    labels: newLabel,
    datasets: [{
        label: 'My First Dataset',
        data: dataTotal,
        backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'green',
            'aquamarine',
            'blueviolet',
            'brown',
            'cadetblue',
            'gray'
        ],
        hoverOffset: 6
    }]
};

const myChart2 = new Chart(ctx2, {
    type: 'doughnut',
    data: data2,
});