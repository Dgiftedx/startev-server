mainApp.factory('adminService', ['$http','$location', '$window', function($http, $location, $window){

    /////////////////////////////////////////////
    //START

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


    function sendNormalData(url, data, onSuccess, onError) {
        $http.get(url, data
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

    function sendFormDataObjects(dt, url, onSuccess, onError) {
        var formData = new FormData();
        //append form data
        for (var i = 0; i < dt.length ; i++) {
            formData.append(dt[i].field, dt[i].value);
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
        fetchData : fetchData,
        sendNormalData : sendNormalData,
        sendFormDataObjects : sendFormDataObjects
    };
}]);