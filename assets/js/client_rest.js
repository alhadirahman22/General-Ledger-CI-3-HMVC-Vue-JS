// var api_token = DATA_SESSION.token;

let API_TOKEN = 'neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm';
let URL_REST = 'https://merchant-api-sandbox.shipper.id/';

const default_header = {
    "X-API-KEY": API_TOKEN,
    "Content-Type": "application/json", // "application/x-www-form-urlencoded",
};

function client_get(endpoint, param, headers = '', URI_HEAD = '') {

    return new Promise(async (resolve, rejected) => {

        var newParam = await genrateParam(param);

        headers = (headers != '') ? headers : default_header;

        URI_HEAD = (URI_HEAD != '') ? URI_HEAD : URL_REST;

        var settings = {
            "url": URI_HEAD + endpoint + newParam,
            "method": "GET",
            "timeout": 0,
            "headers": headers,
            error: function (XMLHttpRequest, textStatus, errorThrown) {

                rejected('error_client_rest');

                if ('<?= ENVIRONMENT ?>' == 'production') {
                    alert("Action failed, please try later");
                    // window.location.replace(base_url+'rest-error/'+XMLHttpRequest.status+'/'+errorThrown);
                } else {
                    console.log(XMLHttpRequest.status, errorThrown);
                    alert("Error, Cek console log");
                }


            }
        };

        $.ajax(settings).done(function (response) {
            // console.log(response);
            resolve(response);
        });

    });

}

function client_post(endpoint, param, headers = '', URI_HEAD = '') {
    return new Promise(async (resolve, rejected) => {

        headers = (headers != '') ? headers : default_header;

        URI_HEAD = (URI_HEAD != '') ? URI_HEAD : URL_REST;

        var settings = {
            "url": URI_HEAD + endpoint,
            "method": "POST",
            "timeout": 0,
            "headers": headers,
            "data": param,
            error: function (XMLHttpRequest, textStatus, errorThrown) {

                rejected('error_client_rest');

                // window.location.replace(base_url+'rest-error/'+XMLHttpRequest.status+'/'+errorThrown);

                if ('<?= ENVIRONMENT ?>' == 'production') {
                    alert("Action failed, please try later");
                    // window.location.replace(base_url+'rest-error/'+XMLHttpRequest.status+'/'+errorThrown);
                } else {
                    console.log(XMLHttpRequest.status, errorThrown);
                    alert("Error, Cek console log");
                }


            }
        };

        $.ajax(settings).done(function (response) {
            // console.log(response);
            resolve(response);
        });

    });
}

function client_put(endpoint, param, headers = '', URI_HEAD = '') {
    return new Promise(async (resolve, rejected) => {

        headers = (headers != '') ? headers : default_header;

        URI_HEAD = (URI_HEAD != '') ? URI_HEAD : URL_REST;

        var settings = {
            "url": URI_HEAD + endpoint,
            "method": "PUT",
            "timeout": 0,
            "headers": headers,
            "data": param,
            error: function (XMLHttpRequest, textStatus, errorThrown) {

                rejected('error_client_rest');
                // window.location.replace(base_url+'rest-error/'+XMLHttpRequest.status+'/'+errorThrown);

                if ('<?= ENVIRONMENT ?>' == 'production') {
                    alert("Action failed, please try later");
                    // window.location.replace(base_url+'rest-error/'+XMLHttpRequest.status+'/'+errorThrown);
                } else {
                    console.log(XMLHttpRequest.status, errorThrown);
                    alert("Error, Cek console log");
                }


            }
        };

        $.ajax(settings).done(function (response) {
            // console.log(response);
            resolve(response);
        });

    });
}

function client_delete(endpoint, param, headers = '', URI_HEAD = '') {
    return new Promise(async (resolve, rejected) => {

        headers = (headers != '') ? headers : default_header;

        URI_HEAD = (URI_HEAD != '') ? URI_HEAD : URL_REST;

        var settings = {
            "url": URI_HEAD + endpoint,
            "method": "DELETE",
            "timeout": 0,
            "headers": headers,
            error: function (XMLHttpRequest, textStatus, errorThrown) {

                rejected('error_client_rest');

                if ('<?= ENVIRONMENT ?>' == 'production') {
                    alert("Action failed, please try later");
                    // window.location.replace(base_url+'rest-error/'+XMLHttpRequest.status+'/'+errorThrown);
                } else {
                    console.log(XMLHttpRequest.status, errorThrown);
                    alert("Error, Cek console log");
                }
            }
        };

        $.ajax(settings).done(function(response){
            resolve(response);
        });


    });
}

function genrateParam(params) {
    return new Promise((resolve, rejected) => {
        try {

            let body = '';
            var listIndex = Object.keys(params);
            for (let i = 0; i < listIndex.length; i++) {
                const element = listIndex[i];
                var spr = (i == 0) ? '?' : '&';
                body = body + spr + element + '=' + params[element];
            }

            resolve(body);
        } catch (err) {
            rejected('error_generate_param');
        }
    });

}