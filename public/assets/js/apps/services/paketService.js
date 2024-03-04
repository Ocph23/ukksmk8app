angular.module('service.paket', [])
    .factory("paketService", paketService);


function paketService($http, $q) {
   var url = "/api/paket"
    return {
        get: get,
        getById: getById,
        getByTahunAjaran: getByTahunAjaran,
        post: post,
        put: put,
        putDetail: putDetail,
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

    function putDetail(data) {
        defer = $q.defer();
        if (data.lenght > 0) {
            result = data.find(x => x.id == id);
            defer.resolve(result);
        } else {
            $http({
                method: "PUT",
                url: url + '/' + data.id+ '/detail',
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