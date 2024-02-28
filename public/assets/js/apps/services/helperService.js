angular.module('service.helper', [])
    .factory("helperService", helperService);

function helperService($http, $q) {
    return {
        getGender: getGender,
        getAccesorTypes:getAccesorTypes
    }

    function getGender() {
        return ["Pria", "Wanita"]
    }
    
    function getAccesorTypes() {
        return ["Internal", "Eksternal"]
    }

}