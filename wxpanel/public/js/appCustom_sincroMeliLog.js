//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.sincroMeliLog = {};
appCustom.sincroMeliLog.detalles = {};
appCustom.sincroMeliLog.INDEX = {'url':appCustom.REST_URL + 'sincroMeliLog', 'verb':'GET'};
appCustom.sincroMeliLog.CREATE = {'url':appCustom.REST_URL + 'sincroMeliLog/create', 'verb':'GET'};
appCustom.sincroMeliLog.STORE = {'url':appCustom.REST_URL + 'sincroMeliLog', 'verb':'POST'};
appCustom.sincroMeliLog.EDIT = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLog/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.sincroMeliLog.detalles.EDIT = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLogDetalles/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.sincroMeliLog.UPDATE = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLog/' + id;
    },
    'verb': 'PUT'
};
appCustom.sincroMeliLog.DELETE = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLog/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.sincroMeliLog.image = {};

appCustom.sincroMeliLog.image.mainView = {
    'url':function(id){
        return 'sincroMeliLog/imageMain/' + id;
    }
};

appCustom.sincroMeliLog.image.INDEX = {'url':appCustom.REST_URL + 'sincroMeliLogImage', 'verb':'GET'};
appCustom.sincroMeliLog.image.STORE = {'url':appCustom.REST_URL + 'sincroMeliLogImage', 'verb':'POST'};
appCustom.sincroMeliLog.image.EDIT = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLogImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.sincroMeliLog.image.UPDATE = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLogImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.sincroMeliLog.image.DELETE = {
    'url':function(id) {
        return appCustom.REST_URL + 'sincroMeliLogImage/' + id;
    },
    'verb': 'DELETE'
};