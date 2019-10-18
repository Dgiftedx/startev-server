mainApp.controller('composeController', ['$rootScope','lodash','$scope','$location','$window','$timeout','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,moment, adminService) {

        $scope.model = {};
        $scope.model.showComposeContainer = false;
        $scope.model.cargando_compose_main = false;
        $scope.model.lock = false;

        $scope.model.userGroups = [
            {id:1, name:"All Users", slug: "all_users"},
            {id:2, name:"Graduates", slug: "graduates"},
            {id:3, name:"Mentors", slug: "mentors"},
            {id:4, name:"Businesses", slug: "businesses"},
            {id:5, name:"Students", slug: "students"}
        ];

        $scope.model.targetGroup = '';
        $scope.model.searchData = {};


        $scope.model.onCheckTargetGroup = function () {
            var newUsersBox = $('#new-users'), groupBox = $('#platform-users');

            if ($scope.model.targetGroup === 'newUser') {
                if (!groupBox.hasClass('hide')) {
                    groupBox.removeClass('fadeInUp');
                    groupBox.addClass('hide');
                }

                newUsersBox.removeClass('hide');
                newUsersBox.addClass('fadeInUp');
                return false;
            }

            if ($scope.model.targetGroup === 'platformUsers') {
                if (!newUsersBox.hasClass('hide')) {
                    newUsersBox.addClass('hide');
                    newUsersBox.removeClass('fadeInUp');
                }

                groupBox.removeClass('hide');
                groupBox.addClass('fadeInUp');
                return false;
            }


            if ($scope.model.targetGroup === 'admins') {
                if (!newUsersBox.hasClass('hide')) {
                    newUsersBox.addClass('hide');
                    newUsersBox.removeClass('fadeInUp');
                }

                if (!groupBox.hasClass('hide')) {
                    groupBox.addClass('hide');
                    groupBox.removeClass('fadeInUp');
                }

                return false;
            }
        };
        
        
        $scope.model.lockSelection = function () {

            // if (lodash.size($scope.model.targetGroup) === 0 || lodash.size($scope.model.searchData) === 0) {
            if (lodash.size($scope.model.targetGroup) === 0) {
                return adminService.alert("Please make selection to determine target users who will receive this mail","warning");
            }


            var data={};
            if ($scope.model.searchData.from && $scope.model.searchData.to && $scope.model.targetGroup === 'newUser') {
                // Check if user is found for the selected date range
                data = {
                    from : $scope.model.searchData.from,
                    to: $scope.model.searchData.to
                };

        }else if ($scope.model.targetGroup === 'platformUsers') {
                // Check if user is found for the selected date range
                data = {
                    user_group : $scope.model.searchData.user_group
                };

            }
            else{
                data={targetGroup:$scope.model.targetGroup}
            }

            adminService.sendNormalData(
                '/mail-manager/check-users',
                data,
                function (resp) {
                    if (resp.data.hasUsers) {
                        $scope.model.showComposeContainer = true;
                        $scope.model.cargando_compose_main = true;
                        $scope.model.searchData.recipients = resp.data.users;

                        $timeout(function () {
                            $scope.model.cargando_compose_main = false;
                        }, 2000);

                        return false;
                    } else {
                        adminService.alert("No users found for selected range", "danger");
                        return false;
                    }

                },

                function (response) {
                    console.log(response);
                }
            )



        };
        $scope.model.lockSelectionExport = function () {

            // if (lodash.size($scope.model.targetGroup) === 0 || lodash.size($scope.model.searchData) === 0) {
            if (lodash.size($scope.model.targetGroup) === 0) {
                return adminService.alert("Please make selection to determine target users who will receive this mail","warning");
            }


            var data={};
            if ($scope.model.searchData.from && $scope.model.searchData.to && $scope.model.targetGroup === 'newUser') {
                // Check if user is found for the selected date range
                data = {
                    from : $scope.model.searchData.from,
                    to: $scope.model.searchData.to
                };

        }else if ($scope.model.targetGroup === 'platformUsers') {
                // Check if user is found for the selected date range
                data = {
                    user_group : $scope.model.searchData.user_group
                };

            }
            else{
                data={targetGroup:$scope.model.targetGroup}
            }
            para = JSON.stringify(data);
            window.open('/mail-manager/check-users-export-mail?params='+para,'_blank');


        };

        $scope.model.clearForm = function () {
            var newUsersBox = $('#new-users'), groupBox = $('#platform-users');
            $scope.model.targetGroup = '';
            $scope.model.searchData = {};
            $scope.model.cargando_compose_main = false;
            $scope.model.showComposeContainer = false;
            if (!newUsersBox.hasClass('hide')) {
                newUsersBox.addClass('hide');
                newUsersBox.removeClass('fadeInUp');
            }

            if (!groupBox.hasClass('hide')) {
                groupBox.addClass('hide');
                groupBox.removeClass('fadeInUp');
            }
        };
        
        
        $scope.model.sendMail = function () {

            $scope.model.searchData.target_group = $scope.model.targetGroup;
            adminService.sendNormalData(
                '/mail-manager/send-mail',
                $scope.model.searchData,
                function (resp) {
                    adminService.alert(resp.data.message, "success");
                    $scope.model.clearForm();
                }
            )
        }

    }]);