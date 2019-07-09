mainApp.factory('adminService', ['$http','$location', '$window', function($http, $location, $window){

    /////////////////////////////////////////////
    //START

    function alert($message, $type) {
        return new Toast({message : $message, type: $type});
    }

    function fetchData(url, onSuccess, onError) {
        $http.get(url
        ).then(function (response) {
            //console.log
            if (response.data && response.data.success) {
                onSuccess(response);
            }
            else {
                onError(response);
            }

        }, function (response) {

            onError(response);

        });
    }

    function sendImageData(url, data, onSuccess, onError) {
        $http.post(url, data,
            {
                headers: { 'Content-Type': undefined},
                //prevents serializing payload.  don't do it.
                transformRequest: angular.identity
            }
        ).then(function (response) {
            //console.log
            if (response.data && response.data.success) {
                onSuccess(response);
            }
            else {
                onError(response);
            }

        }, function (response) {

            onError(response);

        });
    }


    function sendNormalData(url, data, onSuccess, onError) {
        $http.post(url, data
        ).then(function (response) {
            //console.log
            if (response.data && response.data.success) {
                onSuccess(response);
            }
            else {
                onError(response);
            }

        }, function (response) {

            onError(response);

        });
    }

    function addAdminUser(dt, url, onSuccess, onError) {
        var formData = new FormData();
        //append form data
        formData.append('name', dt.name);
        formData.append('username', dt.username);
        formData.append('email', dt.email);
        formData.append('password', dt.password);
        formData.append('avatar', dt.avatar);
        formData.append('role', dt.roles.name);

        if (dt.official_phone) {
            formData.append('official_phone', dt.official_phone)
        }

        if (dt.address){
            formData.append('address', dt.address);
        }

        $http.post(url,formData,
            {
                headers: { 'Content-Type': undefined},
                //prevents serializing payload.  don't do it.
                transformRequest: angular.identity
            }
        ).then(function (response) {
            //console.log
            if (response.data && response.data.success) {
                onSuccess(response);
            }
            else {
                onError(response);
            }

        }, function (response) {

            onError(response);

        });
    }


    return {
        alert : alert,
        fetchData : fetchData,
        addAdminUser : addAdminUser,
        sendNormalData : sendNormalData,
        sendImageData : sendImageData
    };
}]);