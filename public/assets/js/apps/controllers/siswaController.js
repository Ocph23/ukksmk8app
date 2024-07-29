angular
    .module("siswaController", [])
    .controller(
        "siswaController",
        function (
            $scope,
            $http,
            siswaService,
            helperService,
            aksesorService,
            paketService,
            jurusanService,
            tahunajaranService
        ) {
            $scope.helper = helperService;
            document.getElementById("content").style.display = "block";
            $scope.tahunajaran = [];
            $scope.genders = helperService.getGender();
            //get Tahun ajaran
            tahunajaranService.get().then(
                (result) => {
                    $scope.tahunajaran = result;
                    $scope.selectedTahunAjaran = result.find((x) => x.aktif);
                    jurusanService.get().then(
                        (resultJurusan) => {
                            $scope.dataJurusan = resultJurusan;
                            if ($scope.selectedTahunAjaran != null) {
                                $scope.changeSelectedTahunAjaran(
                                    $scope.selectedTahunAjaran
                                );
                            }
                        },
                        (err) => {}
                    );
                },
                (err) => {}
            );

            $scope.changeSelectedTahunAjaran = (tahunajaran) => {
                if (tahunajaran) {
                    //get siswa By tahun ajaran
                    paketService
                        .getByTahunAjaran($scope.selectedTahunAjaran.id)
                        .then(
                            (result) => {
                                $scope.sourcepakets = result;
                                siswaService
                                    .getByTahunAjaran(tahunajaran.id)
                                    .then(
                                        (result) => {
                                            $scope.datasiswa = result;
                                        },
                                        (err) => {}
                                    );
                            },
                            (err) => {}
                        );
                }
            };

            $scope.changeSelectedjurusan = (jurusanid) => {
                if (jurusanid) {
                    $scope.pakets = $scope.sourcepakets.filter(
                        (x) => x.jurusan_id == jurusanid
                    );
                }
            };

            $scope.tambah = () => {
                $scope.model = {};
            };

            $scope.edit = (item) => {
                $scope.changeSelectedjurusan(item.jurusan_id);
                $scope.model = JSON.parse(JSON.stringify(item));
                $scope.model.tanggallahir = new Date($scope.model.tanggallahir);
                $("#exampleModal").modal("show");
            };

            $scope.simpan = (param) => {
                if (!$scope.selectedTahunAjaran) {
                    Swal.fire({
                        title: "Error!",
                        text: "Anda Belum memilih tahun ajaran.",
                        icon: "Error",
                    });
                    return;
                }

                param.tahunajaran_id = $scope.selectedTahunAjaran.id;
                if (!param.id) {
                    siswaService.post(param).then(
                        (result) => {
                            param.id = result.id;
                            $scope.datasiswa.push(param);

                            $scope.model={};
                            setTimeout((x) => {
                                $("#exampleModal").modal("hide");
                            }, 1000);

                            Swal.fire({
                                title: "Tersimpan!",
                                text: "Data berhasil disimpan.",
                                icon: "success",
                            });
                        },
                        (err) => {
                            Swal.fire({
                                title: "Error!",
                                text: err.message,
                                icon: "error",
                            });
                        }
                    );
                } else {
                    siswaService.put(param).then(
                        (result) => {
                            var exsistData = $scope.datasiswa.find(
                                (x) => x.id == param.id
                            );
                            if (exsistData) {
                                exsistData.nis = result.nis;
                                exsistData.nama = result.nama;
                                exsistData.jk = result.jk;
                                exsistData.alamat = result.alamat;
                                exsistData.jurusan = result.jurusan;
                                exsistData.tahunajaran = result.tahunajaran;
                            }
                            $("#exampleModal").modal("hide");
                            Swal.fire({
                                title: "Tersimpan!",
                                text: "Data berhasil disimpan.",
                                icon: "success",
                            });
                        },
                        (err) => {
                            Swal.fire({
                                title: "Error!",
                                text: err.message,
                                icon: "error",
                            });
                        }
                    );
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
                    confirmButtonText: "Yes, delete it!",
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        $http({
                            method: "Delete",
                            url: "/api/siswa/" + data.id,
                            data: data,
                        }).then(
                            function (res) {
                                var index = $scope.data.indexOf(data);
                                $scope.data.splice(index, 1);
                                Swal.fire({
                                    title: "Terhapus!",
                                    text: "Data berhasil dihapus.",
                                    icon: "success",
                                });
                            },
                            function (err) {
                                Swal.fire({
                                    title: "Error",
                                    text: err.data.message,
                                    icon: "error",
                                });
                            }
                        );
                    }
                });
            };

            $scope.nilaiStatus = (data) => {
                if (data.penilaian == null || data.penilaian.length == 0) {
                    return false;
                }
                return true;
            };
        }
    );
