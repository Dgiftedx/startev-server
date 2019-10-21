mainApp.controller('pendingPayoutViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.currentPayout = {};


        //===================== Get users list ====================//
        $scope.model.getPayouts = function () {
            adminService.fetchData('/payouts/index?query=all_pending', function (resp) {
                $scope.model.payouts = resp.data.payouts;
                $scope.model.cargando_main = false;
            }, function () {
                $scope.model.cargando_main = false;
            });
        };

        $scope.model.getPayouts();

        $scope.model.exportPendingPayouts=function () {
            window.open('/payouts/export-pending-payouts','_blank');
        }

        $scope.model.viewDetails = function (payout) {
            $scope.model.currentPayout = payout;

            adminService.fetchData(
                '/payouts/items?type=business&id='+payout.id,
                function(resp){
                    $scope.model.currentPayout.items = resp.data.items;
                    //open modal box;
                    $timeout(function() {
                        $('#businessModal').modal('show');
                    });
                }
            )

        }
        $scope.model.viewDetailsVenture = function (payout) {
            $scope.model.currentPayout = payout;

            adminService.fetchData(
                '/payouts/items?type=business&id='+payout.id,
                function(resp){
                    $scope.model.currentPayout.items = resp.data.items;
                    //open modal box;
                    $timeout(function() {
                        $('#ventureModal').modal('show');
                    });
                }
            )

        }
        $scope.model.markBusinessSettled = function () {
            $scope.settlingBusiness=true;
            adminService.fetchData(
                '/payouts/mark-settled?type=business&id='+$scope.model.currentPayout.id,
                function(resp){
                    if(resp.data.success)
                        adminService.alert("Business has been successfully settled","success");
                    $timeout(function() {
                        $('#businessModal').modal('hide');
                        $scope.model.getPayouts();
                    });
                    $scope.settlingBusiness=false;
                }
            )

        }
        $scope.model.markVentureSettled = function () {
            $scope.settlingBusiness=true;
            adminService.fetchData(
                '/payouts/mark-settled?type=store&id='+$scope.model.currentPayout.id,
                function(resp){
                    if(resp.data.success)
                        adminService.alert("Store has been successfully settled","success");
                    $timeout(function() {
                        $('#ventureModal').modal('hide');
                        $scope.model.getPayouts();
                    });
                    $scope.settlingBusiness=false;
                }
            )

        }

    }]);
