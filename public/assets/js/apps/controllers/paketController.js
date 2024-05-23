angular
    .module("paketController", [])
    .controller(
        "paketController",
        function (
            $scope,
            $http,
            paketService,
            helperService,
            aksesorService,
            jurusanService,
            tahunajaranService
        ) {
            document.getElementById("content").style.display = "block";
            $scope.tahunajaran = [];
            //get Tahun ajaran
            tahunajaranService.get().then(
                (result) => {
                    $scope.tahunajaran = result;
                    $scope.selectedTahunAjaran = result.find((x) => x.aktif);
                    jurusanService.get().then(
                        (resultJurusan) => {
                            $scope.dataJurusan = resultJurusan;
                            aksesorService.get().then(
                                (resultAksesor) => {
                                    $scope.aksesorInternal =
                                        resultAksesor.filter(
                                            (x) => x.jenis == "Internal"
                                        );
                                    $scope.aksesorEksternal =
                                        resultAksesor.filter(
                                            (x) => x.jenis == "Eksternal"
                                        );
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
                },
                (err) => {}
            );

            $scope.changeSelectedTahunAjaran = (tahunajaran) => {
                if (tahunajaran) {
                    //get Paket By tahun ajaran
                    paketService.getByTahunAjaran(tahunajaran.id).then(
                        (result) => {
                            $scope.dataPaket = result;
                        },
                        (err) => {}
                    );
                }
            };

            $scope.tambah = () => {
                $scope.model = { basisnilai: true };
            };

            $scope.edit = (item) => {
                $scope.model = JSON.parse(JSON.stringify(item));
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
                    paketService.post(param).then(
                        (result) => {
                            $scope.dataPaket.push(result);
                            $scope.model = {};

                            Swal.fire({
                                title: "Tersimpan!",
                                text: "Data berhasil disimpan.",
                                icon: "success",
                            });
                            setTimeout(() => {
                                $("#exampleModal").modal("hide");
                            }, 1000);
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
                    paketService.put(param).then(
                        (result) => {
                            var exsistData = $scope.dataPaket.find(
                                (x) => x.id == param.id
                            );
                            if (exsistData) {
                                exsistData.nama = result.nama;
                                exsistData.kode = result.kode;
                                exsistData.deskripsi = result.deskripsi;
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
                            url: "/api/paket/" + data.id,
                            data: data,
                        }).then(
                            function (res) {
                                var index = $scope.dataPaket.indexOf(data);
                                $scope.dataPaket.splice(index, 1);
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
        }
    );
