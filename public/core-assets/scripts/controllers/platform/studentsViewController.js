mainApp.controller('studentsViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.studentData = {};
        $scope.model.roles = [];
        $scope.model.buttonText = '';
        $scope.model.formTitle = '';
        $scope.model.formUrl = '/admin-create-user';
        $scope.model.showMainList = true;
        $scope.model.showUserForm = false;
        $scope.model.sendingUser = false;

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
        $scope.model.getPlatformStudents = function () {
            adminService.fetchData('get-platform-students', function (resp) {
                $scope.model.users = resp.data.students;
            });
        };

        $scope.model.getPlatformStudents();



        //=============== Trigger new user form ======================//
        $scope.model.triggerUserForm = function (type) {
            var userList = $('#main-list'), userForm = $('#user-form');

            if (type === 'add') {
                $scope.model.buttonText = 'Create User';
                $scope.model.formTitle = 'Add New User';
            }else{
                $scope.model.buttonText = 'Update User';
                $scope.model.formTitle = 'Edit user account';
            }
            userList.toggle(400);
            userForm.toggle(500);
        };

        //==================== update user avatar =====================//
        $('#avatar_upload').on('change', function () {
            readURL(this);
            //upload to server
            var file = $(this).prop("files");
            $scope.model.studentData.avatar = file[0];
        });


        $scope.model.closeForm = function () {
            $scope.model.studentData = {};
            $scope.model.formUrl = '/admin-create-user';
            $scope.model.getAdminUsers();
            $scope.model.triggerUserForm();
            $('#avatar_preview').attr('src', '/core-assets/defaults/avatar.jpg');
            $('#avatar_upload').val("");
        };


        $scope.model.savestudentData = function () {

            if (lodash.size($scope.model.studentData.name) === 0) {
                adminService.alert("Full name is required", "warning");
                return false;
            }

            if (lodash.size($scope.model.studentData.username) === 0) {
                adminService.alert("Username is required", "warning");
                return false;
            }

            if (lodash.size($scope.model.studentData.email) === 0) {
                adminService.alert("Email is required","warning");
                return false;
            }

            if (!$scope.model.studentData.id) {
                if (lodash.size($scope.model.studentData.password) === 0) {
                    adminService.alert("You must enter a valid password for this user", "danger");
                    return false;
                }
            }

            if (lodash.size($scope.model.studentData.roles) === 0) {
                adminService.alert("You must set user access role", "danger");
                return false;
            }

            $scope.model.sendingUser = true;

            console.log($scope.model.formUrl);

            adminService.addAdminUser($scope.model.studentData,$scope.model.formUrl,
            function (resp) {
                adminService.alert(resp.data.message, "success");
                $scope.model.sendingUser = false;
               $scope.model.closeForm();

            }, function (resp) {
                adminService.alert(resp.data.error, "danger");
            })
        };


        $scope.model.editUser = function (user) {
            $scope.model.studentData = {};
            $scope.model.formUrl = '/admin-update-user/'+user.id;
            $scope.model.studentData.id = user.id;
            $scope.model.studentData.name = user.name;
            $scope.model.studentData.email = user.email;
            $scope.model.studentData.official_phone = user.official_phone;
            $scope.model.studentData.address = user.address;
            $scope.model.studentData.username = user.username;
            $scope.model.studentData.roles = user.roles[0];

            if (lodash.size(user.avatar) > 0) {
                $('#avatar_preview').attr('src', user.avatar);
            }

            $scope.model.triggerUserForm('edit');

        };

        $scope.model.deleteStudentAccount = function (user) {
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
                            $scope.model.getPlatformStudents();
                        },
                        function (resp) {
                            adminService.alert("Error removing student account from system","error");
                        }
                    )
                }else{
                    SweetAlert.show("Operation Cancelled", "Student account is safe", "error");
                }
            });
        };


    }]);