mainApp.controller('dashboardController', ['$rootScope','lodash','$scope','$location','$window','$timeout','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,moment, adminService) {

        $scope.model = {};


        $scope.model.getDashboardCounts = function () {
            adminService.fetchData('/dashboard/get-stats',
                function (resp) {
                    $scope.model.stats = resp.data.result;
                    //prepare chart Data
                    // $scope.model.prepareBarChart(resp.data.result.chartData);
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

            var datas = {};
            var datasChild = [];
            datas.name = 'statistics';
            datas.data = [];

            var yearsGroup = [];

            angular.forEach(data, function (item, key) {
                datasChild.push(key);
                yearsGroup.push({index: key, values: []});

                angular.forEach(item, function (value, index) {
                    yearsGroup[index].values.push(value);
                    // datas.data.push({name: index, data : value});
                })
            });

            console.log(datasChild, yearsGroup);

            if(datas){
                $timeout(function () {
                    angular.copy([datas], $scope.model.statisticsData);
                    angular.copy(datasChild, $scope.model.statisticschildData);
                    $scope.model.statisticsChart = true;
                },0)
            }
            else{
                $scope.model.statisticsChart = true;
            }
        };

    }]);