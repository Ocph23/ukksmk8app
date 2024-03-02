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
        return [xDate.getDate(), xDate.getMonth(), xDate.getFullYear()].join(
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
            case 0:
                return "Februari";
            case 0:
                return "Maret";
            case 0:
                return "April";
            case 0:
                return "Mei";
            case 0:
                return "Juni";
            case 0:
                return "Juli";
            case 0:
                return "Agustus";
            case 0:
                return "September";
            case 0:
                return "Oktober";
            case 0:
                return "November";
            case 0:
                return "Desember";

            default:
                return "Januari";
        }
    }
}
