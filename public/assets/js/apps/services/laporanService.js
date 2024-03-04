angular.module("service.laporan", []).factory("laporanService", laporanService);

function laporanService($http, $q) {
    var url = "/api/laporan";
    return {
        kelulusan: kelulusan,
    };

    function kelulusan(ta, jurusan) {
        defer = $q.defer();
        $http({
            method: "GET",
            url: url + "/" + ta + "/" + jurusan,
        }).then(
            function (res) {
                defer.resolve(res.data);
            },
            function (err) {
                defer.reject(err);
            }
        );
        return defer.promise;
    }
}
