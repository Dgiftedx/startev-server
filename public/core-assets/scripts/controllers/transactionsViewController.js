mainApp.controller('transactionsViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.queryParams = {};
        $scope.model.sendingUser = false;
        $scope.model.cargando_main = true;
        $scope.model.searchTerm = 'all';
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

        $scope.model.getTransactions();


        $scope.model.resetFilter = function () {
            $scope.model.cargando_main = true;
            $scope.model.queryParams = {};
            $scope.model.searchTerm = 'all';
            $scope.model.getTransactions();
        };


        $scope.model.refineParams = function () {

            var params = '';
            if ($scope.model.queryParams.unpaid) {
                params += "unpaid&";
            }

            if ($scope.model.queryParams.paid){
                params += "status=paid";
            }

            var lastString = params.charAt(params.length - 1);
            if (lastString === '&') {
                params.slice(0, -1);
            }

            $scope.model.searchTerm = params;
            $scope.model.cargando_main = true;
            $scope.model.getTransactions();
        };



        $scope.model.viewDetails = function (transaction) {
            $scope.model.currentTransaction = transaction;
            //open modal box;
            $('#transactionModal').modal('show');
        }

    }]);
