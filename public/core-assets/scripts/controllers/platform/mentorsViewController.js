
mainApp.controller('mentorsViewController', ['$rootScope','lodash','$scope','$location','$window','$timeout','SweetAlert','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,SweetAlert,moment, adminService) {

        $scope.model = {};
        $scope.model.mentorData = {};
        $scope.model.roles = [];
        $scope.model.buttonText = '';
        $scope.model.formTitle = '';
        $scope.model.formUrl = '/platform-create-mentor';
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
        $scope.model.getPlatformMentors = function () {
            adminService.fetchData('get-platform-mentors', function (resp) {
                $scope.model.users = resp.data.mentors;
                $scope.model.fields = resp.data.fields;
            });
        };

        $scope.model.getPlatformMentors();



        //=============== Trigger new user form ======================//
        $scope.model.triggerUserForm = function (type) {
            var userList = $('#main-list'), userForm = $('#user-form');

            if (type === 'add') {
                $scope.model.buttonText = 'Create Mentor Account';
                $scope.model.formTitle = 'Add New Mentor';
            }else{
                $scope.model.buttonText = 'Update Mentor Account';
                $scope.model.formTitle = 'Edit mentor account';
            }
            userList.toggle(400);
            userForm.toggle(500);
        };

        //==================== update user avatar =====================//
        $('#avatar_upload').on('change', function () {
            readURL(this);
            //upload to server
            var file = $(this).prop("files");
            $scope.model.mentorData.avatar = file[0];
        });


        $scope.model.closeForm = function () {
            $scope.model.mentorData = {};
            $scope.model.formUrl = '/platform-create-mentor';
            $scope.model.getPlatformMentors();
            $scope.model.triggerUserForm();
            $('#avatar_preview').attr('src', '/core-assets/defaults/avatar.jpg');
            $('#avatar_upload').val("");
        };


        $scope.model.saveMentorData = function () {

            if (lodash.size($scope.model.mentorData.name) === 0) {
                adminService.alert("Full name is required", "warning");
                return false;
            }


            if (lodash.size($scope.model.mentorData.email) === 0) {
                adminService.alert("Email is required","warning");
                return false;
            }

            if (!$scope.model.mentorData.id) {
                if (lodash.size($scope.model.mentorData.password) === 0) {
                    adminService.alert("You must enter a valid password for this user", "danger");
                    return false;
                }
            }

            $scope.model.sendingUser = true;

            adminService.addMentorAccount($scope.model.mentorData,$scope.model.formUrl,
            function (resp) {
                adminService.alert(resp.data.message, "success");
                $scope.model.sendingUser = false;
               $scope.model.closeForm();

            }, function (resp) {
                $scope.model.sendingUser = false;
                adminService.alert(resp.data.error, "danger");
            })
        };


        $scope.model.editMentor = function (user) {
            $scope.model.mentorData = {};
            $scope.model.formUrl = '/platform-update-mentor/'+user.id;
            $scope.model.mentorData.id = user.id;
            $scope.model.mentorData.name = user.name;
            $scope.model.mentorData.email = user.email;
            $scope.model.mentorData.address = user.address;
            if (lodash.size(user.roleData.data.organization) > 0) {
                $scope.model.mentorData.organization = user.roleData.data.organization;
            }

            if (lodash.size(user.roleData.data.current_job_position) > 0) {
                $scope.model.mentorData.current_job_position = user.roleData.data.current_job_position;
            }

            if (lodash.size(user.avatar) > 0) {
                $('#avatar_preview').attr('src', user.avatar);
            }

            $scope.model.triggerUserForm('edit');

        };

        $scope.model.deleteMentorAccount = function (user) {
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
                            $scope.model.getPlatformMentors();
                        },
                        function (resp) {
                            adminService.alert("Error removing mentor account from system","error");
                        }
                    )
                }else{
                    SweetAlert.show("Operation Cancelled", "Mentor account is safe", "error");
                }
            });
        };


    }]);