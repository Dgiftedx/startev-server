mainApp.controller('verificationRequestsController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};


        $scope.model.loadRequests = function () {
            adminService.fetchData('/verification/get-requests', function (resp) {
                $scope.model.requests = resp.data.results;
            })
        };

        $scope.model.loadRequests();


        $scope.model.downloadFile = function (file) {
            var path = file.replace("/uploads/", "");
            adminService.offLoad("/download-verification-file/"+path);
        };


        $scope.model.verify = function (request) {
            adminService.fetchData('/account-verification/verify/'+request.id, function (resp) {
                adminService.alert("Account verified.","success");
               $scope.model.loadRequests();
            });
        };


        $scope.model.reject = function (request) {
            adminService.fetchData('/account-verification/reject/'+request.id, function (resp) {
                adminService.alert("Account verification rejected.","success");
                $scope.model.loadRequests();
            });
        }


    }]);