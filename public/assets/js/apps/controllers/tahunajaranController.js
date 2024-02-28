angular.module('tahunajaranController', [])
    .controller('tahunajaranController', function ($scope, $http, tahunajaranService) {
        $model = {};
        $scope.data = [];

        tahunajaranService.get()
            .then(result => {
                $scope.data = result;
            },
                err => { });

        $scope.tambah = () => {
            $scope.model = {}
        };

        $scope.edit = (item) => {
            $scope.model = JSON.parse(JSON.stringify(item));
            $('#exampleModal').modal('show')
        };

        $scope.simpan = (param) => {
            if (!param.id) {
                tahunajaranService.post(param)
                    .then(result => {
                        $scope.data.push(result);

                        Swal.fire({
                            title: "Tersimpan!",
                            text: "Data berhasil disimpan.",
                            icon: "success"
                        });
                        setTimeout(x => {
                            $('#exampleModal').modal('hide')
                        }, 500)

                    }, err => {
                        Swal.fire({
                            title: "Error!",
                            text: err.message,
                            icon: "error"
                        });
                    });
            } else {
                tahunajaranService.put(param)
                    .then(result => {
                        var exsistData = $scope.data.find(x => x.id == param.id);
                        if (exsistData) {
                            exsistData.nama = result.nama;
                            exsistData.kode = result.kode;
                            exsistData.deskripsi = result.deskripsi;
                        }
                        $('#exampleModal').modal('hide')
                        Swal.fire({
                            title: "Tersimpan!",
                            text: "Data berhasil disimpan.",
                            icon: "success"
                        });
                    }, err => {
                        Swal.fire({
                            title: "Error!",
                            text: err.message,
                            icon: "error"
                        });
                    });
            }


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
