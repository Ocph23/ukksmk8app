angular.module('siswaController', [])
    .controller('siswaController', function ($scope, $http, siswaService, helperService, aksesorService,
        paketService, jurusanService, tahunajaranService) {
        $scope.tahunajaran = [];
        $scope.genders = helperService.getGender();
        //get Tahun ajaran
        tahunajaranService.get()
            .then(result => {
                $scope.tahunajaran = result;
                jurusanService.get()
                    .then(resultJurusan => {
                        $scope.dataJurusan = resultJurusan;
                    }, err => { });
            }, err => { });





        $scope.changeSelectedTahunAjaran = (tahunajaran) => {
            if (tahunajaran) {
                //get siswa By tahun ajaran
                paketService.getByTahunAjaran($scope.selectedTahunAjaran.id)
                    .then(result => {
                        $scope.sourcepakets = result;
                        siswaService.getByTahunAjaran(tahunajaran.id)
                            .then(result => {
                                $scope.datasiswa = result;
                            },
                                err => { });
                    },
                        err => { });

            }
        }

        $scope.changeSelectedjurusan = (jurusanid) => {
            if (jurusanid) {
                $scope.pakets = $scope.sourcepakets.filter(x => x.jurusan_id == jurusanid);

            }
        }


        $scope.tambah = () => {
            $scope.model = {}
        };

        $scope.edit = (item) => {
            $scope.changeSelectedjurusan(item.jurusan_id);
            $scope.model = JSON.parse(JSON.stringify(item));
            $('#exampleModal').modal('show')
        };

        $scope.simpan = (param) => {

            if (!$scope.selectedTahunAjaran) {
                Swal.fire({
                    title: "Error!",
                    text: "Anda Belum memilih tahun ajaran.",
                    icon: "Error"
                });
                return;
            }

            param.tahunajaran_id = $scope.selectedTahunAjaran.id;
            if (!param.id) {
                siswaService.post(param)
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
                siswaService.put(param)
                    .then(result => {
                        var exsistData = $scope.datasiswa.find(x => x.id == param.id);
                        if (exsistData) {
                            exsistData.nis = result.nis;
                            exsistData.nama = result.nama;
                            exsistData.jk = result.jk;
                            exsistData.alamat = result.alamat;
                            exsistData.jurusan = result.jurusan;
                            exsistData.tahunajaran = result.tahunajaran;
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
                        url: '/api/siswa/' + data.id,
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
