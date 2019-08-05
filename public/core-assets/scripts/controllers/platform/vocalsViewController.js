mainApp.controller('vocalsViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.showMainList = true;
        $scope.model.showUserForm = false;
        $scope.model.sendingUser = false;

        //===================== Get users list ====================//
        $scope.model.getPlatformVocals = function () {
            adminService.fetchData('/platform-vocals/get-all', function (resp) {
                $scope.model.vocals = resp.data.vocals;
            });
        };

        $scope.model.getPlatformVocals();


        $scope.model.count = function(items) {
            return lodash.size(items);
        };

        $scope.model.deleteVocal = function (vocal) {
            SweetAlert.show({
                title: "Are you sure?",
                text: "This will remove the vocal and all referral records attached",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, Keep Data",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then( function (isConfirm) {
                if(isConfirm){

                    adminService.fetchData(
                        '/platform-vocals/delete-vocal/'+vocal.id,
                        function (resp) {
                            SweetAlert.show("Success", resp.data.message, "success");
                            $scope.model.getPlatformVocals();
                        },
                        function () {
                            adminService.alert("Error removing vocal profile from system","error");
                        }
                    )
                }else{
                    SweetAlert.show("Operation Cancelled", "Vocal account is safe", "error");
                }
            });
        };


    }]);