mainApp.controller('siteDataViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.reloading = false;
        $scope.model.newSitedata = {};


        // Get bank statistics on system
        $scope.model.fetchSiteData = function() {
            adminService.fetchData(
                '/general-settings/fetch-site-data',
                function(resp){
                    $scope.model.siteData = resp.data.siteData;
                    // "for(data in $scope.model.siteData){
                    //     data.name = data.key;
                    // }
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

        $scope.model.updateSiteData = function(id, value) {
            $scope.model.reloading = true;

            $scope.model.newSitedata = {};
            // console.log("Am here", $scope.model.newSitedata);

            adminService.updateDataSvr(
                'site-data-update/'+id+'/'+value,
                function(resp) {
                    if (resp.data && resp.data.error) {
                        adminService.alert("Data update successfully","success");
                    }
                    else {
                        adminService.alert("Error occurred!!!",'error');
                    }
                    $scope.model.reloading = false;
                },
                function() {
                    $scope.model.reloading = false;
                }
            )
        };


    }]);
