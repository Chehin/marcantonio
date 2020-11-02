<script>
	
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidosBackup = {};
appCustom.pedidosBackup.INDEX = {'url':appCustom.REST_URL + 'pedidosBackup', 'verb':'GET'};
appCustom.pedidosBackup.CREATE = {'url':appCustom.REST_URL + 'pedidosBackup/create', 'verb':'GET'};
appCustom.pedidosBackup.STORE = {'url':appCustom.REST_URL + 'pedidosBackup', 'verb':'POST'};
appCustom.pedidosBackup.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosBackup/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosBackup.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosBackup/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidosBackup.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosBackup/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.pedidosBackup.metodopago = {};
appCustom.pedidosBackup.metodopago.STORE = {'url':appCustom.REST_URL + 'pedidoMetodopago', 'verb':'POST'};
appCustom.pedidosBackup.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoMetodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosBackup.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoMetodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.pedidosBackup.estadopago = {};
appCustom.pedidosBackup.estadopago.STORE = {'url':appCustom.REST_URL + 'pedidoEstadopago', 'verb':'POST'};
appCustom.pedidosBackup.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosBackup.estadopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadopago/' + id;
    },
    'verb': 'PUT'
};
//estadoenvio
appCustom.pedidosBackup.estadoenvio = {};
appCustom.pedidosBackup.estadoenvio.STORE = {'url':appCustom.REST_URL + 'pedidoEstadoenvio', 'verb':'POST'};
appCustom.pedidosBackup.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosBackup.estadoenvio.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadoenvio/' + id;
    },
    'verb': 'PUT'
};
//productos
appCustom.pedidosBackup.productos = {};
appCustom.pedidosBackup.productos.STORE = {'url':appCustom.REST_URL + 'pedidoProductos', 'verb':'POST'};
appCustom.pedidosBackup.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoProductos/' + id + '/edit';
    },
    'verb': 'GET'
};

</script>
