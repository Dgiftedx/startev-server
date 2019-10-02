
mainApp.controller('businessesViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.businessData = {};
        $scope.model.roles = [];
        $scope.model.buttonText = '';
        $scope.model.formTitle = '';
        $scope.model.formUrl = '/platform-create-business';
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
        $scope.model.getPlatformBusinesses = function () {
            adminService.fetchData('get-platform-businesses', function (resp) {
                $scope.model.businesses = resp.data.businesses;
                $scope.model.fields = resp.data.fields;
                $scope.model.cargando_main = false;
            });
        };

        $scope.model.getPlatformBusinesses();

        //=============== Trigger new user form ======================//
        $scope.model.triggerUserForm = function (type) {
            var userList = $('#main-list'), userForm = $('#user-form');

            if (type === 'add') {
                $scope.model.buttonText = 'Create Business Account';
                $scope.model.formTitle = 'Add New Business';
            }else{
                $scope.model.buttonText = 'Update Business Account';
                $scope.model.formTitle = 'Edit business account';
            }
            userList.toggle(400);
            userForm.toggle(500);
        };

        //==================== update user avatar =====================//
        $('#avatar_upload').on('change', function () {
            readURL(this);
            //upload to server
            var file = $(this).prop("files");
            $scope.model.businessData.avatar = file[0];
        });


        $scope.model.closeForm = function () {
            $scope.model.businessData = {};
            $scope.model.formUrl = '/platform-create-business';
            $scope.model.getPlatformBusinesses();
            $scope.model.triggerUserForm();
            $('#avatar_preview').attr('src', '/core-assets/defaults/avatar.jpg');
            $('#avatar_upload').val("");
        };


        $scope.model.saveBusinessData = function () {

            if (lodash.size($scope.model.businessData.name) === 0) {
                adminService.alert("Business name is required", "warning");
                return false;
            }


            if (lodash.size($scope.model.businessData.email) === 0) {
                adminService.alert("Email is required","warning");
                return false;
            }

            if (!$scope.model.businessData.id) {
                if (lodash.size($scope.model.businessData.password) === 0) {
                    adminService.alert("You must enter a valid password for this user", "danger");
                    return false;
                }
            }

            $scope.model.sendingUser = true;

            adminService.addBusinessAccount($scope.model.businessData,$scope.model.formUrl,
            function (resp) {
                adminService.alert(resp.data.message, "success");
                $scope.model.sendingUser = false;
               $scope.model.closeForm();

            }, function (resp) {
                $scope.model.sendingUser = false;
                adminService.alert(resp.data.error, "danger");
            })
        };


        $scope.model.editBusiness = function (user) {
            $scope.model.businessData = {};
            $scope.model.formUrl = '/platform-update-business/'+user.id;
            $scope.model.businessData.id = user.id;
            $scope.model.businessData.name = user.name;
            $scope.model.businessData.email = user.email;
            $scope.model.businessData.address = user.address;

            if (lodash.size(user.roleData.data.description) > 0) {
                $scope.model.businessData.description = user.roleData.data.description;
            }

            if (lodash.size(user.roleData.data.phone) > 0) {
                $scope.model.businessData.phone = user.roleData.data.phone;
            }

            if (lodash.size(user.avatar) > 0) {
                $('#avatar_preview').attr('src', user.avatar);
            }

            $scope.model.triggerUserForm('edit');

        };

        $scope.model.deleteBusinessAccount = function (user) {
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
                            $scope.model.getPlatformBusinesses();
                        },
                        function (resp) {
                            adminService.alert("Error removing business account from system","error");
                        }
                    )
                }else{
                    SweetAlert.show("Operation Cancelled", "Business account is safe", "error");
                }
            });
        };


    }]);