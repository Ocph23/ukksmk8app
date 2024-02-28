angular.module('service.penilaian', [])
    .factory("penilaianService", penilaianService);


function penilaianService($http, $q) {
    var url = "/api/penilaian"
    return {
        get: get,
        getById: getById,
        getBySiswaId: getBySiswaId,
        post: post,
        put: put,
        delete: remove,
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

    function getBySiswaId(id) {
        defer = $q.defer();
        $http({
            method: "GET",
            url: url + "/siswa/" + id,
        }).then(function (res) {
            defer.resolve(res.data);
        }, function (err) {
            defer.reject(err);
        })
        return defer.promise;
    }



    function post(data) {
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