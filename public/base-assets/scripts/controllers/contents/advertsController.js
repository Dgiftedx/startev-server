mainApp.controller('advertsController', ['$rootScope','lodash','$scope','$location','$window','$timeout','moment','adminService',
    function ($rootScope,lodash, $scope, $location, $window, $timeout,moment, adminService) {

        $scope.model = {};
        $scope.model.bannerData = {};
        $scope.model.showMain = true;
        $scope.model.showForm = false;

        $scope.model.advertBanners = function () {
          adminService.fetchData(
              '/manage-contents/get-adverts',
              function (resp) {
                  $scope.model.adverts = resp.data.adverts;
                  $scope.model.cargando_main = false;
              },
              function () {
                  $scope.model.cargando_main = false;
              }
          )
        };

        $scope.model.advertBanners();


        $scope.model.newBanner = function() {
            $scope.model.bannerData = {};
            $scope.model.showMain = false;
            $scope.model.showForm = true;

            $timeout(function() {
                $(document).find('#banner').dropify();
            }, 300);
        }

        $scope.model.close = function() {
            $scope.model.showForm = false;
            $scope.model.showMain = true;
            $('.dropify-clear').click();
        }

        $scope.model.saveBanner = function() {

            if(lodash.size($scope.model.bannerData.link) === 0 ){
                return adminService.alert("link field is empty","error");
            }
            if($('#banner').prop('files').length > 0){
                $scope.model.bannerData.image = $('#banner').prop('files')[0];
            }else{
                $scope.model.bannerData.image = null;
            }

            adminService.uploadBanner(
                '/manage-contents/upload-banner',
                $scope.model.bannerData,
                function(resp) {
                    adminService.alert(resp.data.message, "success");
                    $scope.model.close();
                    $scope.model.advertBanners();
                }
            )
        }

        $scope.model.status = function(advert, status) {
            adminService.fetchData(
                '/manage-contents/update-advert/'+advert.id+'/'+status,
                function() {
                    $scope.model.advertBanners();
                }
            )
        }

        $scope.model.delete = function (advert) {

            adminService.fetchData(
                '/manage-contents/delete-advert/'+advert.id,
                function (resp) {
                    $scope.model.advertBanners();
                    adminService.alert(resp.data.message, "success");
                }
            )
        }

    }]);
