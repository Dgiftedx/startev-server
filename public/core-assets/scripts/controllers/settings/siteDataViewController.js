mainApp.controller('siteDataViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.cargando_main = true;
        $scope.model.reloading = false;
        $scope.model.newSitedata = {};
        $scope.model.feedSetting = 0;
        $scope.model.feedSettingtrue =false;


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

            adminService.fetchData(
                '/manage-contents/feeds/getsetting',
                function(resp){
                    $scope.model.feedSetting = resp.data.result;
                    console.log($scope.model.feedSetting);
                    if($scope.model.feedSetting == 1){
                        $scope.model.feedSettingtrue = true;
                    }else{
                        $scope.model.feedSettingtrue = false;
                    }
                    $scope.model.banksCount = resp.data.banksCount;
                    $scope.model.cargando_main = false;
                }
            )
        };

        $scope.model.changeFeedSetting = function () {
            $scope.model.reloading = true;

            adminService.fetchData(
                '/manage-contents/feeds/updateFeedSetting',
                function(resp) {
                    adminService.alert("Data update successfully","success");
                    $scope.model.feedSetting = resp.data.result;
                    console.log($scope.model.feedSetting);
                    $scope.model.fetchSiteData();
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
