helpTipsController.jsmainApp.controller('helpTipsController', ['$rootScope','lodash','$scope','$location','$window','$timeout','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,moment, adminService) {

        $scope.model = {};
        $scope.model.currentTip = {};
        $scope.model.showMain = true;
        $scope.model.showForm = false;
        $scope.model.cargando_main = true;
        $scope.model.formUrl = '';

        $scope.model.userGroups = [
            {id:1, name:"Graduate", slug: "graduate"},
            {id:2, name:"Mentor", slug: "mentor"},
            {id:3, name:"Business", slug: "business"},
            {id:4, name:"Student", slug: "student"}
        ];
        
        $scope.model.loadHelpTips = function () {
          adminService.fetchData(
              '/manage-contents/get-help-tips',
              function (resp) {
                  $scope.model.helpTips = resp.data.helpTips;
                  $scope.model.cargando_main = false;
              },
              function () {
                  $scope.model.cargando_main = false;
              }
          )  
        };

        $scope.model.loadHelpTips();


        $scope.model.editTip = function (tip) {
            $scope.model.showMain = false;
            $scope.model.showForm = true;
            $scope.model.formUrl = '/manage-contents/update-help-tip/'+tip.id;
            $scope.model.currentTip = tip;
            var search = lodash.findLast($scope.model.userGroups, ['slug', tip.target]);

            $scope.model.currentTip.target = search.slug;
        };


        $scope.model.goBack = function () {
            $scope.model.showMain = true;
            $scope.model.showForm = false;
            $scope.model.currentTip = {};
        };


        $scope.model.submitTip = function () {
          
            var data = {
                title : $scope.model.currentTip.title,
                target: $scope.model.currentTip.target,
                content : $scope.model.currentTip.content
            };
            
            adminService.sendNormalData(
                $scope.model.formUrl,
                data,
                function (resp) {
                    $scope.model.loadHelpTips();
                    adminService.alert(resp.data.message);
                    $scope.model.goBack();
                }
            )
        };


        $scope.model.addNewTip = function () {
            $scope.model.showMain = false;
            $scope.model.showForm = true;
            $scope.model.formUrl = '/manage-contents/store-help-tip';
            $scope.model.currentTip = {};
        };


        $scope.model.deleteTip = function (tip) {

            adminService.fetchData(
                '/manage-contents/delete-help-tip/'+tip.id,
                function (resp) {
                    $scope.model.loadHelpTips();
                    adminService.alert(resp.data.message, "success");
                }
            )
        }

    }]);