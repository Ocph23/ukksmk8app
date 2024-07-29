angular.module("service.helper", []).factory("helperService", helperService);

function helperService($http, $q) {
    return {
        getGender: getGender,
        getAccesorTypes: getAccesorTypes,
        toDate: toDate,
        getPenetapan:getPenetapan
    };

    function getGender() {
        return ["Pria", "Wanita"];
    }

    function getAccesorTypes() {
        return ["Internal", "Eksternal"];
    }

    function toDate(date) {
        if (!date) return "";
        var xDate = new Date(date);
        return [xDate.getDate(), xDate.getMonth()+1, xDate.getFullYear()].join(
            "-"
        );
    }

    function getPenetapan(date) {
        if (!date) return "";
        var xDate = new Date(date);
        return [
            xDate.getDate(),
            getBulan(xDate.getMonth()),
            xDate.getFullYear(),
        ].join(" ");
    }

    function getBulan(bulan) {
        switch (bulan) {
            case 0:
                return "Januari";
            case 1:
                return "Februari";
            case 2:
                return "Maret";
            case 3:
                return "April";
            case 4:
                return "Mei";
            case 5:
                return "Juni";
            case 6:
                return "Juli";
            case 7:
                return "Agustus";
            case 8:
                return "September";
            case 9:
                return "Oktober";
            case 10:
                return "November";
            case 11:
                return "Desember";

            default:
                return "Januari";
        }
    }
}
