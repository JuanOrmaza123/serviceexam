<?php
    include "header.php";
?>
<div class="container" ng-app="orderApp" ng-cloak>
    <div class="row" ng-controller="orderController">
        <div class="col-12">
            <h4>Ordenes</h4>
            <div class="row form-group">
                <div class="col-12 col-sm-6">
                    <button class="btn btn-secondary" ng-click="showModalCreate()">Nueva orden</button>
                </div>
                <div class="col-12 col-sm-3 offset-sm-3">
                    <div class="input-group mb-3">
                        <input ng-model="search" type="text" class="form-control" placeholder="Buscar ordenes" aria-label="Buscar ordenes" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-show="showAlertNoDeleteOrder" class="alert alert-danger alert-dismissible fade show" role="alert">
                No puede eliminar esta orden porque tiene items asociados
            </div>

            <div ng-show="showAlertNoCloseOrder" class="alert alert-danger alert-dismissible fade show" role="alert">
                No puede cerrar esta orden porque no tiene items asociados
            </div>

            <div class="containerTable">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Numero de orden</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in orders | filter:search">
                                <td>{{ order.order_number }}</td>
                                <td>{{ order.name }}</td>
                                <td><span ng-if="order.status == 0">Abierta</span> <span ng-if="order.status == 1">Cerrada</span></td>
                                <td>
                                    <i class="far fa-edit cursor icon-margin" title="Editar Orden" ng-click="showModalUpdateOrder(order)"></i>
                                    <i class="fas fa-trash cursor icon-margin" title="Eliminar Orden" ng-click="deleteOrder(order.id)"></i>
                                    <i class="fas fa-lock cursor icon-margin" title="Cerrar Orden" ng-click="closeOrder(order.id)" ng-if="order.status == 0"></i>
                                    <i class="fas fa-lock-open cursor icon-margin" title="Reabrir Orden" ng-click="openOrder(order.id)" ng-if="order.status == 1"></i>
                                    <i class="fab fa-searchengin cursor icon-margin" title="Ver items" ng-click="viewItems(order)"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h4>Items - Orden {{ orderSelected.order_number }} </h4>
            <div class="row form-group">
                <div class="col-12 col-sm-6">
                    <button class="btn btn-secondary" ng-click="showModalCreateItem()">Nuevo item</button>
                </div>
                <div class="col-12 col-sm-3 offset-sm-3">
                    <div class="input-group mb-3">
                        <input ng-model="searchItems" type="text" class="form-control" placeholder="Buscar items" aria-label="Buscar items" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="containerTable">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in items | filter:searchItems">
                                <td>{{ item.sku }}</td>
                                <td>{{ item.name }}</td>
                                <td><span ng-if="item.type == 0">Servicio</span> <span ng-if="item.type == 1">Articulo</span></td>
                                <td>$ {{ item.price | number:0 }}</td>
                                <td>
                                    <i class="far fa-edit cursor icon-margin" title="Editar Item" ng-click="showModalUpdateitem(item)"></i>
                                    <i class="fas fa-trash cursor icon-margin" title="Eliminar Orden" ng-click="deleteItem(item.id)"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-8 offset-sm-2 mb-5">
            <h4>Reportes</h4>
            <div class="row form-group">
                <div class="col-12 col-sm-6">
                    <label for="clientReport">Cliente</label>
                    <select id="clientReport" ng-model="report.client" class="form-control" ng-change="getOrdersForClient()" ng-options="client.id as client.name for client in clientsReport"></select>
                </div>
                <div class="col-12 col-sm-6">
                    <label for="orderReport">Orden</label>
                    <select id="orderReport" ng-model="report.order" class="form-control" ng-disabled="disabledSelectReportOrder" ng-options="order.id as order.order_number for order in ordersReport"></select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" ng-click="generateReport()" class="btn btn-primary">Generar</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Añadir nueva orden</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div ng-show="showAlertOrderNumber" class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Ese numero de orden ya existe, prueba con otro
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form ng-submit="createOrder()">
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label for="order_number">Numero de orden</label>
                                            <input type="text" id="order_number" class="form-control" ng-model="order.order_number">
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="client_id">Cliente</label>
                                            <select ng-model="order.client_id" class="form-control" id="client_id" ng-options="client.id as client.name for client in clients"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="updateOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar orden</h5>
                        <button type="button" class="close" ng-click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form ng-submit="updateOrder()">
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label for="order_number">Numero de orden</label>
                                            <input type="text" id="order_number" class="form-control" ng-model="order.order_number">
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="client">Cliente</label>
                                            <select ng-model="order.client_id" class="form-control" id="client_id" ng-options="client.id as client.name for client in clients"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Añadir nuevo item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form ng-submit="createItem()">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="sku">SKU</label>
                                            <input type="number" id="sku" class="form-control" ng-model="item.sku">
                                        </div>
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="name">Nombre</label>
                                            <input type="text" id="name" class="form-control" ng-model="item.name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="type">Tipo</label>
                                            <select ng-model="item.type" id="type" ng-options="type.value as type.label for type in optionsType" class="form-control"></select>
                                        </div>
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="price">Precio</label>
                                            <input type="number" id="price" class="form-control" ng-model="item.price">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateItemModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Actualizar item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="closeModalItem()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form ng-submit="updateItem()">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="sku">SKU</label>
                                            <input type="number" id="sku" class="form-control" ng-model="item.sku">
                                        </div>
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="name">Nombre</label>
                                            <input type="text" id="name" class="form-control" ng-model="item.name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="type">Tipo</label>
                                            <select ng-model="item.type" id="type" ng-options="type.value as type.label for type in optionsType" class="form-control"></select>
                                        </div>
                                        <div class="col-12 col-sm-6 form-group">
                                            <label for="price">Precio</label>
                                            <input type="number" id="price" class="form-control" ng-model="item.price">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include "footer.php";
?>