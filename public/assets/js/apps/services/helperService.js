angular.module('service.helper', [])
    .factory("helperService", helperService);

function helperService($http, $q) {
    return {
        getGender: getGender,
        getAccesorTypes: getAccesorTypes,
        toDate:toDate
    }

    function getGender() {
        return ["Pria", "Wanita"]
    }

    function getAccesorTypes() {
        return ["Internal", "Eksternal"]
    }



    function toDate(date){
        if(!date) return "";
        var xDate = new Date(date);
        return [xDate.getDate(), xDate.getMonth(), xDate.getFullYear()].join('-');
    }



}



