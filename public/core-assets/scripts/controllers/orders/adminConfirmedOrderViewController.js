mainApp.controller('adminConfirmedOrderViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.finalizing = false;
        $scope.model.loadOrdersIndex = function () {

            adminService.fetchData(
                '/admin/order/get-orders-index/confirmed',
                function (resp) {
                    console.log(resp.data);
                    $scope.model.orders = resp.data.orders;
                    $scope.model.cargando_main = false;
                },
                function () {
                    console.log("An error occurred");
                }
            );
        };

        $scope.model.loadOrdersIndex();



        $scope.model.conclude = function(id){
            $scope.model.finalizing = true;

            adminService.fetchData(
                '/admin/order/conclude-order/'+id,
                function(resp) {
                    adminService.alert("All settlements has been done. Order process completed.");
                    $scope.model.loadOrdersIndex();
                    $scope.model.finalizing = false;
                }
            )
        }
    }]);
