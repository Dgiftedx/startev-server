mainApp.controller('dashboardController', ['$rootScope','lodash','$scope','$location','$window','$timeout','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,moment, adminService) {

        $scope.model = {};


        $scope.model.getDashboardCounts = function () {
            adminService.fetchData('/dashboard/get-stats',
                function (resp) {
                    $scope.model.stats = resp.data.result;
                    console.log($scope.model.stats );
                    //prepare chart Data
                    $scope.model.prepareBarChart(resp.data.result.chartData);
                })
        };


        //get dashboard stats
        $scope.model.getDashboardCounts();


        $scope.model.statisticsData = [];
        $scope.model.statisticschildData =[];
        $scope.model.statisticsChart = false;

        $scope.model.prepareBarChart = function (data) {
            $scope.model.statisticsChart = false;

            angular.copy([], $scope.model.statisticsData);
            angular.copy([], $scope.model.statisticschildData);

            var datas = [];
            var datasChild = [];
            datas.name = 'statistics';

            datasChild = data.index;

            angular.forEach(data.data, function (item) {
                datas.push(item);
            });

            if(datas){
                $timeout(function () {
                    angular.copy(datas, $scope.model.statisticsData);
                    angular.copy(datasChild, $scope.model.statisticschildData);
                    $scope.model.statisticsChart = true;
                },0)
            }
            else{
                $scope.model.statisticsChart = true;
            }
        };

    }]);