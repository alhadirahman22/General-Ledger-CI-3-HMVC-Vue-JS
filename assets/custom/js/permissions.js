$(document).ready(function(){
    console.log(dataModule);
});

$('#name').blur(function () {
    let name = $('#name').val();
    if (name != '') {
        let array = name.split('/');
        let new_val = '';
        for (let i = 0; i < array.length; i++) {
            const element = array[i];
            var spa = (i != (array.length - 1)) ? ' - ' : '';
            new_val = new_val + upwords('' + element) + spa;
        }
        $('#definition').val(new_val);
    }


});

function upwords(str) {
    str = str.replace(/_/g,' ');
    str = str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });

    return str;
}