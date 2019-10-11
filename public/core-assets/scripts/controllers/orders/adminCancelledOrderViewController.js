mainApp.controller('adminCancelledOrderViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};

        $scope.model.cargando_main = true;



        $scope.model.loadOrdersIndex = function () {

            adminService.fetchData(
                '/admin/order/get-orders-index/cancelled',
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

    }]);