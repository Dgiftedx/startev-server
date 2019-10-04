mainApp.controller('deliveryViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.currentTransaction = {};


        //===================== Get users list ====================//
        $scope.model.getTransactions = function () {
            adminService.fetchData('/transaction/get-index?status='+$scope.model.searchTerm, function (resp) {
                $scope.model.transactions = resp.data.settlements;
                angular.forEach($scope.model.transactions, function (item) {
                    item.total = item.total + item.delivery;
                });
                $scope.model.cargando_main = false;
            }, function () {
                $scope.model.cargando_main = false;
            });
        };

        // $scope.model.getTransactions();


        $scope.model.viewDetails = function (transaction) {
            $scope.model.currentTransaction = transaction;
            //open modal box;
            $('#transactionModal').modal('show');
        }

    }]);
