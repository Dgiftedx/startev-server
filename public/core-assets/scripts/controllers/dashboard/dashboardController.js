mainApp.controller('dashboardController', ['$rootScope','lodash','$scope','$location','$window','$timeout','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout, adminService) {


    $scope.model = {};


    $scope.model.getDashboardCounts = function () {
        adminService.fetchData('/dashboard/get-stats',
        function (resp) {

        },
        function (resp) {
            
        })
    };


}]);