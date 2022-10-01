$(document).ready(function () {
    console.log(dataModule);
});

$('#employee_id').change(function () {
    getEmployee();
});

function getEmployee() {
    let employee_id = $('#employee_id').val();
    if (employee_id != '' && employee_id != null && employee_id != 'null'
        && typeof employee_id !== 'undefined') {
        $.getJSON(dataModule.module_url + 'getemployee?employee_id=' + employee_id, function (result) {
            if (result.length > 0) {
                $('#email').val(result[0].email);
                let spl_name = result[0].name.split(' ');
                let firstname = spl_name[0].toLowerCase().trim();
                let lasetname = spl_name[spl_name.length - 1].toLowerCase().trim();
                lasetname = (lasetname != firstname) ? lasetname : '';
                $('#username').val(firstname + '' + lasetname);

                let birthday = result[0].date_of_birth;
                if(birthday!='' && birthday!=null && birthday!='null'){
                    let pass = moment(birthday).format('DDMMYY');
                    $('#password').val(pass);
                }

            } else {
                $('#email').val('');
            }
        });
    }
}