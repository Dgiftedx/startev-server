mainApp.controller('adminNewOrderViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.currentOrder = {};
        $scope.model.showMain = true;
        $scope.model.showDetails = false;
        $scope.model.cargando_main = true;



        $scope.model.loadOrdersIndex = function () {

            adminService.fetchData(
                '/admin/order/get-orders-index/new',
                function (resp) {
                    $scope.model.orders = resp.data.orders;
                    $scope.model.cargando_main = false;
                },
                function () {
                    console.log("An error occurred");
                }
            );
        };

        $scope.model.loadOrdersIndex();

        $scope.model.count = function(items) {
            return lodash.size(items);
        };


        $scope.model.viewDetails = function(item){
            $scope.model.currentOrder = item;
            $scope.model.showMain = false;
            $scope.model.showDetails = true;
        };


        $scope.model.close = function(){
            $scope.model.currentOrder = {};
            $scope.model.showMain = true;
            $scope.model.showDetails = false;
        };

        $scope.model.dispatchOrder = function(order){
            console.log(order);
            adminService.fetchData(
                '/admin/confirm-order/'+order.id,
                function(resp) {
                    $scope.model.close();
                    $scope.model.loadOrdersIndex();
                    adminService.alert("Order Dispatched. You can now check confirmed order list for further actions");
                }
            )
        }

    }]);
