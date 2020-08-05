angular.module('orderApp', [])
    .controller('orderController', function($http, $scope) {
        $scope.orders = [];
        $scope.items = [];
        $scope.clients = [];
        $scope.client = {};
        $scope.order = {};
        $scope.item = {};
        $scope.orderSelected = {};
        $scope.clientsReport = [];
        $scope.ordersReport = [];
        $scope.disabledSelectReportOrder = true;
        $scope.report = {};
        $scope.optionsType = [{
                'value': 0,
                'label': 'Servicio'
            },
            {
                'value': 1,
                'label': 'Articulo'
            }
        ];
        $scope.showAlertNoDeleteOrder = false;
        $scope.showAlertOrderNumber = false;
        $scope.showAlertNoCloseOrder = false;

        $scope.getClients = function() {
            $http.post('/controllers/Clients.php', { action: 'list' }).
            then(function(response) {
                $scope.clientsReport.push({ 'id': '0', 'name': 'Todos los clientes' });
                angular.forEach(response.data, function(value, key) {
                    $scope.clients.push(value);
                    $scope.clientsReport.push(value)
                });
            }, function(response) {

            });
        };

        $scope.getOrders = function() {
            $http.post('/controllers/Orders.php', { action: 'list' }).
            then(function(response) {
                angular.forEach(response.data, function(value, key) {
                    if (!$scope.orderSelected.id) {
                        $scope.orderSelected = value;
                    }
                    $scope.orders.push(value);
                });
                $scope.resetDataItems();
            }, function(response) {

            });
        };

        $scope.showModalCreate = function() {
            $('#createOrderModal').modal('show');
        };

        $scope.createOrder = function() {
            $scope.order.action = "create";
            $http.post('/controllers/Orders.php', $scope.order).
            then(function(response) {
                if (response.data == 'true') {
                    $('#createOrderModal').modal('hide');
                    $scope.closeModal();
                } else if (response.data == '-1') {
                    $scope.showAlertOrderNumber = true;
                }
            }, function(response) {

            });
        };

        $scope.showModalUpdateOrder = function(order) {
            $scope.order = order;
            $('#updateOrderModal').modal('show');
        }

        $scope.updateOrder = function() {
            $scope.order.action = "update";
            $http.post('/controllers/Orders.php', $scope.order).
            then(function(response) {
                if (response.data == 'true') {
                    $scope.closeModal();
                }
            }, function(response) {

            });
        };

        $scope.deleteOrder = function(id) {
            $scope.order.action = "delete";
            $scope.order.id = id;
            var r = confirm("Esta seguro de eliminar esta orden?");
            if (r == true) {
                $http.post('/controllers/Orders.php', $scope.order).
                then(function(response) {
                    if (response.data == "true") {
                        $scope.resetDataOrders();
                        $scope.showAlertNoDeleteOrder = false;
                    } else if (response.data == "-1") {
                        $scope.showAlertNoDeleteOrder = true;
                    }
                }, function(response) {

                });
            }
        };

        $scope.closeOrder = function(id) {
            $scope.order.action = "close";
            $scope.order.id = id;
            var r = confirm("Esta seguro de cerrar esta orden?");
            if (r == true) {
                $http.post('/controllers/Orders.php', $scope.order).
                then(function(response) {
                    if (response.data == "true") {
                        $scope.resetDataOrders();
                        $scope.showAlertNoCloseOrder = false;
                    } else if (response.data == "-1") {
                        $scope.showAlertNoCloseOrder = true;
                    }
                }, function(response) {

                });
            }
        };

        $scope.openOrder = function(id) {
            $scope.order.action = "open";
            $scope.order.id = id;
            var r = confirm("Esta seguro de re-abrir esta orden?");
            if (r == true) {
                $http.post('/controllers/Orders.php', $scope.order).
                then(function(response) {
                    if (response.data == "true") {
                        $scope.resetDataOrders();
                    }
                }, function(response) {

                });
            }
        };

        $scope.closeModal = function() {
            $('#updateOrderModal').modal('hide');
            $scope.resetDataOrders();
        };

        $scope.resetDataOrders = function() {
            $scope.order = {};
            $scope.orders = [];
            $scope.getOrders();
        };

        $scope.viewItems = function(order) {
            $scope.orderSelected = order;
            $scope.resetDataItems();
        };

        $scope.getItems = function() {
            $http.post('/controllers/Items.php', { action: 'list', orderId: $scope.orderSelected.id }).
            then(function(response) {
                angular.forEach(response.data, function(value, key) {
                    $scope.items.push(value);
                });
            }, function(response) {

            });
        };
        $scope.showModalCreateItem = function() {
            $('#createItemModal').modal('show');
        };

        $scope.createItem = function() {
            $scope.item.order_id = $scope.orderSelected.id;
            $scope.item.action = "create";
            $http.post('/controllers/Items.php', $scope.item).
            then(function(response) {
                if (response.data == 'true') {
                    $('#createItemModal').modal('hide');
                    $scope.resetDataItems();
                }
            }, function(response) {

            });
        };

        $scope.showModalUpdateitem = function(item) {
            $scope.item = item;
            $scope.item.type = parseInt($scope.item.type);
            $scope.item.price = parseInt($scope.item.price);
            $('#updateItemModal').modal('show');
        }

        $scope.updateItem = function() {
            $scope.item.action = "update";
            $http.post('/controllers/Items.php', $scope.item).
            then(function(response) {
                if (response.data == 'true') {
                    $scope.closeModalItem();
                }
            }, function(response) {

            });
        };

        $scope.deleteItem = function(id) {
            $scope.item.action = "delete";
            $scope.item.id = id;
            var r = confirm("Esta seguro de eliminar este item?");
            if (r == true) {
                $http.post('/controllers/Items.php', $scope.item).
                then(function(response) {
                    if (response.data == "true") {
                        $scope.resetDataItems();
                    }
                }, function(response) {

                });
            }
        };

        $scope.resetDataItems = function() {
            $scope.items = [];
            $scope.item = {};
            $scope.getItems();
        };

        $scope.closeModalItem = function() {
            $('#updateItemModal').modal('hide');
            $scope.resetDataItems();
        };

        $scope.getOrdersForClient = function() {
            $scope.ordersReport = [];
            $scope.disabledSelectReportOrder = false;
            if ($scope.report.client == 0) {
                $scope.ordersReport = [{ 'id': '0', 'order_number': 'Todas las ordenes' }];
            } else {
                $scope.ordersReport = [{ 'id': '0', 'order_number': 'Todas las ordenes' }];
                $scope.getListOrdersForClient($scope.report.client);
            }
        };

        $scope.getListOrdersForClient = function(client_id) {
            $http.post('/controllers/Orders.php', { action: 'list', client_id: client_id }).
            then(function(response) {
                angular.forEach(response.data, function(value, key) {
                    $scope.ordersReport.push(value);
                });

            }, function(response) {

            });
        };

        $scope.generateReport = function() {
            $http.post('/controllers/Orders.php', { action: 'report', report: $scope.report }).
            then(function(response) {
                console.log(response);
                window.location = "/resources/data.csv";
            }, function(response) {

            });
        };


        $scope.getOrders();
        $scope.getClients();
    });