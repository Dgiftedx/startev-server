mainApp.controller('graduatesViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.graduateData = {};
        $scope.model.roles = [];
        $scope.model.buttonText = '';
        $scope.model.formTitle = '';
        $scope.model.formUrl = '/platform-create-graduate';
        $scope.model.showMainList = true;
        $scope.model.showUserForm = false;
        $scope.model.sendingUser = false;
        $scope.model.cargando_main = true;

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

        //===================== Get users list ====================//
        $scope.model.getPlatformGraduates = function () {
            adminService.fetchData('get-platform-graduates', function (resp) {
                $scope.model.users = resp.data.graduates;
                $scope.model.fields = resp.data.fields;
                $scope.model.cargando_main = false;
            });
        };

        $scope.model.getPlatformGraduates();



        //=============== Trigger new user form ======================//
        $scope.model.triggerUserForm = function (type) {
            var userList = $('#main-list'), userForm = $('#user-form');

            if (type === 'add') {
                $scope.model.buttonText = 'Create Graduate Account';
                $scope.model.formTitle = 'Add New Graduate';
            }else{
                $scope.model.buttonText = 'Update Graduate Account';
                $scope.model.formTitle = 'Edit graduate account';
            }
            userList.toggle(400);
            userForm.toggle(500);
        };

        //==================== update user avatar =====================//
        $('#avatar_upload').on('change', function () {
            readURL(this);
            //upload to server
            var file = $(this).prop("files");
            $scope.model.graduateData.avatar = file[0];
        });


        $scope.model.closeForm = function () {
            $scope.model.graduateData = {};
            $scope.model.formUrl = '/platform-create-graduate';
            $scope.model.getPlatformGraduates();
            $scope.model.triggerUserForm();
            $('#avatar_preview').attr('src', '/core-assets/defaults/avatar.jpg');
            $('#avatar_upload').val("");
        };


        $scope.model.saveStudentData = function () {

            if (lodash.size($scope.model.graduateData.name) === 0) {
                adminService.alert("Full name is required", "warning");
                return false;
            }


            if (lodash.size($scope.model.graduateData.email) === 0) {
                adminService.alert("Email is required","warning");
                return false;
            }

            if (lodash.size($scope.model.graduateData.institution) === 0) {
                adminService.alert("User institute is required","warning");
                return false;
            }

            if (!$scope.model.graduateData.id) {
                if (lodash.size($scope.model.graduateData.password) === 0) {
                    adminService.alert("You must enter a valid password for this user", "danger");
                    return false;
                }
            }

            if (lodash.size($scope.model.graduateData.careerPath) === 0) {
                adminService.alert("You must set user's career field", "danger");
                return false;
            }

            $scope.model.sendingUser = true;

            adminService.addStudentAccount($scope.model.graduateData,$scope.model.formUrl,
            function (resp) {
                adminService.alert(resp.data.message, "success");
                $scope.model.sendingUser = false;
               $scope.model.closeForm();

            }, function (resp) {
                $scope.model.sendingUser = false;
                adminService.alert(resp.data.error, "danger");
            })
        };


        $scope.model.editGraduate = function (user) {
            $scope.model.graduateData = {};
            $scope.model.formUrl = '/platform-update-graduate/'+user.id;
            $scope.model.graduateData.id = user.id;
            $scope.model.graduateData.name = user.name;
            $scope.model.graduateData.email = user.email;
            $scope.model.graduateData.address = user.address;
            $scope.model.graduateData.institution = user.roleData.data.institution;
            $scope.model.graduateData.careerPath = lodash.findLast($scope.model.fields, ['name', user.roleData.data.careerPath]);

            if (lodash.size(user.avatar) > 0) {
                $('#avatar_preview').attr('src', user.avatar);
            }

            $scope.model.triggerUserForm('edit');

        };

        $scope.model.deleteGraduateAccount = function (user) {
            SweetAlert.show({
                title: "Are you sure?",
                text: "You will not be able to retrieve this user data",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, Keep Data",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then( function (isConfirm) {
                if(isConfirm){

                    adminService.fetchData(
                        '/platform-delete-student/'+user.id,
                        function (resp) {
                            SweetAlert.show("Success", resp.data.message, "success");
                            $scope.model.getPlatformGraduates();
                        },
                        function (resp) {
                            adminService.alert("Error removing graduate account from system","error");
                        }
                    )
                }else{
                    SweetAlert.show("Operation Cancelled", "Graduate user account is safe", "error");
                }
            });
        };


    }]);