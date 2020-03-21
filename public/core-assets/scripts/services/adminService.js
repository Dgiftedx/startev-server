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


    function offLoad(url) {
        return window.open(url, "_blank");
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



    function uploadBanner(url, data, onSuccess, onError) {

        var formData = new FormData();

        formData.append('link', data.link);
        formData.append('banner', data.image);

        $http.post(url, formData,
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



    function addStudentAccount(dt, url, onSuccess, onError) {
        var formData = new FormData();
        //append form data
        formData.append('name', dt.name);
        formData.append('email', dt.email);
        formData.append('phone', dt.phone);
        formData.append('password', dt.password);
        formData.append('institution', dt.institution);
        formData.append('avatar', dt.avatar);
        formData.append('careerPath', dt.careerPath.name);

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




    function addMentorAccount(dt, url, onSuccess, onError) {
        var formData = new FormData();
        //append form data
        formData.append('name', dt.name);
        formData.append('email', dt.email);
        formData.append('password', dt.password);
        formData.append('avatar', dt.avatar);
        if (dt.organization) {
            formData.append('organization', dt.organization);
        }

        if (dt.current_job_position) {
            formData.append('current_job_position', dt.current_job_position);
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



    function addBusinessAccount(dt, url, onSuccess, onError) {
        var formData = new FormData();
        //append form data
        formData.append('name', dt.name);
        formData.append('email', dt.email);
        formData.append('password', dt.password);
        formData.append('avatar', dt.avatar);
        if (dt.description) {
            formData.append('description', dt.description);
        }

        if (dt.phone) {
            formData.append('phone', dt.phone);
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

    function updateDataSvr(url, onSuccess, onError) {
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


    return {
        alert : alert,
        offLoad : offLoad,
        fetchData : fetchData,
        uploadBanner : uploadBanner,
        addAdminUser : addAdminUser,
        sendNormalData : sendNormalData,
        sendImageData : sendImageData,
        addMentorAccount : addMentorAccount,
        addStudentAccount : addStudentAccount,
        addBusinessAccount : addBusinessAccount,
        updateDataSvr : updateDataSvr
    };
}]);
