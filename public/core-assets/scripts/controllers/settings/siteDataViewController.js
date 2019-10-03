mainApp.controller('siteDataViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.reloading = false;


        // Get bank statistics on system
        $scope.model.fetchSiteData = function() {
            adminService.fetchData(
                '/general-settings/fetch-site-data',
                function(resp){
                    $scope.model.siteData = resp.data.siteData;
                    $scope.model.banksCount = resp.data.banksCount;
                    $scope.model.cargando_main = false;
                }
            )
        };


        $scope.model.fetchSiteData();


        $scope.model.reloadBanks = function() {
            $scope.model.reloading = true;

            adminService.fetchData(
                '/general-settings/reload-banks',
                function(resp) {
                    $scope.model.banksCount = resp.data.banksCount;
                    adminService.alert("Banks data reloaded successfully","success");
                    $scope.model.reloading = false;
                },
                function() {
                    $scope.model.reloading = false;
                }
            )
        };
    }]);
