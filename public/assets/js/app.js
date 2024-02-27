angular.module('app', [])
    .config(function ($interpolateProvider) {

        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');

    })
    .controller('tahunAjaranController', function ($scope, $http) {
        $model = {};
        $scope.data = [];
        $http({
            method: 'GET',
            url: '/api/tahunajaran'
        }).then(function (res) {
            // With the data succesfully returned, call our callback
            $scope.data = res.data;
        }, function () {
            alert("error");
        })


        $scope.tambah = () => {
            $scope.model = {}
            $scope.model.tahun = new Date().getFullYear();
        };

        $scope.edit = (item) => {
            $scope.model = item
            $('#exampleModal').modal('show')
        };

        $scope.simpan = (data) => {
            var method = 'POST';
            var url = '/api/tahunajaran';
            if (data.id) {
                method = "PUT";
                url += "/" + data.id
            }

            $http({
                method: method,
                url: url,
                data: data
            }).then(function (res) {
                if (!data.id) {
                    $scope.data.push(res.data);
                }
                $('#exampleModal').modal('hide')
                Swal.fire({
                    title: "Tersimpan!",
                    text: "Data berhasil disimpan.",
                    icon: "success"
                });
            }, function (err) {
                alert(err.data.message);
            })
        };


        $scope.delete = (data) => {
            Swal.fire({
                title: "Yakin Hapus Data ?",
                text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    $http({
                        method: 'Delete',
                        url: '/api/tahunajaran/' + data.id,
                        data: data
                    }).then(function (res) {
                        var index = $scope.data.indexOf(data);
                        $scope.data.splice(index, 1);
                        Swal.fire({
                            title: "Terhapus!",
                            text: "Data berhasil dihapus.",
                            icon: "success"
                        });
                    }, function (err) {
                        alert(err.data.message);
                    })
                }
            });
        }

    })

    .controller('jurusanController', function ($scope, $http) {
        $model = {};
        $scope.data = [];
        $http({
            method: 'GET',
            url: '/api/jurusan'
        }).then(function (res) {
            // With the data succesfully returned, call our callback
            $scope.data = res.data;
        }, function () {
            alert("error");
        })


        $scope.tambah = () => {
            $scope.model = {}
            $scope.model.tahun = new Date().getFullYear();
        };

        $scope.edit = (item) => {
            $scope.model = item
            $('#exampleModal').modal('show')
        };

        $scope.simpan = (data) => {
            var method = 'POST';
            var url = '/api/jurusan';
            if (data.id) {
                method = "PUT";
                url += "/" + data.id
            }

            $http({
                method: method,
                url: url,
                data: data
            }).then(function (res) {
                if (!data.id) {
                    $scope.data.push(res.data);
                }
                $('#exampleModal').modal('hide')
                Swal.fire({
                    title: "Tersimpan!",
                    text: "Data berhasil disimpan.",
                    icon: "success"
                });
            }, function (err) {
                alert(err.data.message);
            })
        };


        $scope.delete = (data) => {
            Swal.fire({
                title: "Yakin Hapus Data ?",
                text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    $http({
                        method: 'Delete',
                        url: '/api/jurusan/' + data.id,
                        data: data
                    }).then(function (res) {
                        var index = $scope.data.indexOf(data);
                        $scope.data.splice(index, 1);
                        Swal.fire({
                            title: "Terhapus!",
                            text: "Data berhasil dihapus.",
                            icon: "success"
                        });
                    }, function (err) {
                        alert(err.data.message);
                    })
                }
            });
        }

    })



    ;