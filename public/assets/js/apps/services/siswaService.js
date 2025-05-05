angular.module('service.siswa', [])
    .factory("siswaService", siswaService);


function siswaService($http, $q) {
    var url = "/api/siswa"
    return {
        get: get,
        getById: getById,
        getByTahunAjaran: getByTahunAjaran,
        post: post,
        put: put,
        delete: remove,
        updateSertifikat: updateSertifikat
    }

    function get() {
        defer = $q.defer();
        $http({
            method: "GET",
            url: url,
        }).then(function (res) {
            defer.resolve(res.data);
        }, function (err) {
            defer.reject(err);
        })
        return defer.promise;
    }

    function getById(id) {
        defer = $q.defer();
        $http({
            method: "GET",
            url: url + "/" + id,
        }).then(function (res) {
            defer.resolve(res.data);
        }, function (err) {
            defer.reject(err);
        })
        return defer.promise;
    }

    function getByTahunAjaran(id) {
        defer = $q.defer();
        $http({
            method: "GET",
            url: url + "/bytahunajaran/" + id,
        }).then(function (res) {
            defer.resolve(res.data);
        }, function (err) {
            defer.reject(err);
        })
        return defer.promise;
    }


    function post(data) {

        data.tanggallahir = new Date(data.tanggallahir).toISOString().slice(0, 10);


        defer = $q.defer();
        $http({
            method: "POST",
            url: url,
            data: data
        }).then(function (res) {
            defer.resolve(res.data);
        }, function (err) {
            defer.reject(err.data);
        })
        return defer.promise;
    }




    function put(data) {
        defer = $q.defer();
        if (data.lenght > 0) {
            result = data.find(x => x.id == id);
            defer.resolve(result);
        } else {
            data.tanggallahir = new Date(data.tanggallahir).toISOString().slice(0, 10);
            $http({
                method: "PUT",
                url: url + '/' + data.id,
                data: data
            }).then(function (res) {
                defer.resolve(res.data);
            }, function (err) {
                defer.reject(err);
            })
        }
        return defer.promise;
    }

    function updateSertifikat(data) {
        defer = $q.defer();
        if (data.lenght > 0) {
            result = data.find(x => x.id == id);
            defer.resolve(result);
        } else {
            data.tanggalpenetapan = new Date(data.tanggalpenetapan).toISOString().slice(0, 10);

            if (data.tanggalcetak != null) {
                data.tanggalcetak = new Date(data.tanggalcetak).toISOString().slice(0, 10);
            } else {
                data.tanggalcetak = null;
            }

            if (data.tanggalambil != null) {
                data.tanggalambil = new Date(data.tanggalambil).toISOString().slice(0, 10);
            } else {
                data.tanggalambil = null;
            }

            $http({
                method: "PUT",
                url: url + '/' + data.id + "/sertifikat",
                data: data
            }).then(function (res) {
                defer.resolve(res.data);
            }, function (err) {
                defer.reject(err);
            })
        }
        return defer.promise;
    }





    function remove(id) {
        defer = $q.defer();
        $http({
            method: "DELETE",
            url: url + '/' + id
        }).then(function (res) {
            defer.resolve(res.data);
        }, function (err) {
            defer.reject(err);
        })
        return defer.promise;
    }
}
