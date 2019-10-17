mainApp.controller('businessViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.currentPayout = {};


        //===================== Get users list ====================//
        $scope.model.getPayouts = function () {
            adminService.fetchData('/payouts/index?query=business', function (resp) {
                $scope.model.payouts = resp.data.payouts;
                $scope.model.cargando_main = false;
            }, function () {
                $scope.model.cargando_main = false;
            });
        };

        $scope.model.getPayouts();


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

    }]);
