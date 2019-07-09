mainApp.controller('adminUserViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.userData = {};
        $scope.model.currentUser = {};
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
        $scope.model.getAdminUsers = function () {
            adminService.fetchData('get-admin-users', function (resp) {
                $scope.model.users = resp.data.users;
                $scope.model.roles = resp.data.roles;
            });
        };

        $scope.model.getAdminUsers();



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
            $scope.model.userData.avatar = file[0];
        });


        $scope.model.closeForm = function () {
            $scope.model.userData = {};
            $scope.model.formUrl = '/admin-create-user';
            $scope.model.getAdminUsers();
            $scope.model.triggerUserForm();
            $('#avatar_preview').attr('src', '/core-assets/defaults/avatar.jpg');
            $('#avatar_upload').val("");
        };


        $scope.model.saveUserData = function () {

            if (lodash.size($scope.model.userData.name) === 0) {
                adminService.alert("Full name is required", "warning");
                return false;
            }

            if (lodash.size($scope.model.userData.username) === 0) {
                adminService.alert("Username is required", "warning");
                return false;
            }

            if (lodash.size($scope.model.userData.email) === 0) {
                adminService.alert("Email is required","warning");
                return false;
            }

            if (!$scope.model.userData.id) {
                if (lodash.size($scope.model.userData.password) === 0) {
                    adminService.alert("You must enter a valid password for this user", "danger");
                    return false;
                }
            }

            if (lodash.size($scope.model.userData.roles) === 0) {
                adminService.alert("You must set user access role", "danger");
                return false;
            }

            $scope.model.sendingUser = true;
            adminService.addAdminUser($scope.model.userData,$scope.model.formUrl,
            function (resp) {
                adminService.alert(resp.data.message, "success");
                $scope.model.sendingUser = false;
               $scope.model.closeForm();

            }, function (resp) {
                adminService.alert(resp.data.error, "danger");
            })
        };


        $scope.model.editUser = function (user) {
            $scope.model.userData = {};
            $scope.model.formUrl = '/admin-update-user/'+user.id;
            $scope.model.userData.id = user.id;
            $scope.model.userData.name = user.name;
            $scope.model.userData.email = user.email;
            $scope.model.userData.official_phone = user.official_phone;
            $scope.model.userData.address = user.address;
            $scope.model.userData.username = user.username;
            $scope.model.userData.roles = user.roles[0];

            if (lodash.size(user.avatar) > 0) {
                $('#avatar_preview').attr('src', user.avatar);
            }

            $scope.model.triggerUserForm('edit');

        };

        $scope.model.deleteAdminUser = function (user) {
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
                        '/delete-admin/'+user.id,
                        function (resp) {
                            SweetAlert.show("Success", resp.data.message, "success");
                            $scope.model.getAdminUsers()
                        },
                        function (resp) {
                            adminService.alert("Error removing user from system","error");
                        }
                    )
                }else{
                    SweetAlert.show("Operation Cancelled", "Staff Profile is safe", "error");
                }
            });
        };


    }]);