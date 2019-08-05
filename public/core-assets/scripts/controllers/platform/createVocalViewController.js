mainApp.controller('createVocalViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.vocalProfileData = {};

        $scope.model.saveVocalData = function () {

            if (lodash.size($scope.model.vocalProfileData.name) === 0) {
                return adminService.alert("Full name is required", "danger");
            }

            if (lodash.size($scope.model.vocalProfileData.email) === 0) {
                return adminService.alert("E-mail address is required", "danger");
            }

            if (lodash.size($scope.model.vocalProfileData.phone) === 0) {
                return adminService.alert("Phone Number is required", "danger");
            }


            var data = {
                name : $scope.model.vocalProfileData.name,
                email : $scope.model.vocalProfileData.email,
                phone : $scope.model.vocalProfileData.phone,
            };

            if (lodash.size($scope.model.vocalProfileData.designation) !== 0) {
                data.designation = $scope.model.vocalProfileData.designation;
            }

            if (lodash.size($scope.model.vocalProfileData.institution) !== 0) {
                data.designation = $scope.model.vocalProfileData.institution;
            }

            if (lodash.size($scope.model.vocalProfileData.address) !== 0) {
                data.designation = $scope.model.vocalProfileData.address;
            }

            $scope.model.sendingVocal = false;

            adminService.sendNormalData(
                '/platform-vocals/store',
                data,
                function () {
                    adminService.alert("Vocal created successfully. Open (view vocals referrals) to view more.", "success");
                    $scope.model.clearForm();
                },
                function (resp) {
                    adminService.alert(resp.data.error, "danger");
                }
            );
        };

        $scope.model.clearForm = function () {
          $scope.model.vocalProfileData = {};
        };

    }]);