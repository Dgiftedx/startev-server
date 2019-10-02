mainApp.controller('profileController', ['$rootScope','lodash','$scope','$location','$window','$timeout','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,moment, adminService) {

        $scope.model = {};
        $scope.model.sendingProfile = false;

        //=============== Read AvatarPreview =================//
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#avatar_preview').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }



        //================== Get user profile =====================//
        $scope.model.getProfile = function () {
            adminService.fetchData('/get-user-profile',
                function (resp) {
                    $scope.model.profile = resp.data.profile;
                });
        };

        $scope.model.getProfile();



        //==================== Update user profile ==================//
        $scope.model.updateProfile = function () {
            $scope.model.sendingProfile = true;
            
            adminService.sendNormalData('/update-profile/'+$scope.model.profile.id,$scope.model.profile,
            function (resp) {
                $scope.model.profile = resp.data.profile;
                $scope.model.sendingProfile = false;
            });
        };


        //==================== update user avatar =====================//
        $('#avatar_upload').on('change', function () {
            readURL(this);


            //upload to server
            var file = $(this).prop("files");
            var formData = new FormData();
            formData.append('avatar', file[0]);

            adminService.sendImageData('/update-profile-avatar/'+$scope.model.profile.id, formData,
            function (resp) {
               $scope.model.profile = resp.data.profile;
            });


            $timeout(function () {
                window.location.reload();
            }, 800);
        });


    }]);