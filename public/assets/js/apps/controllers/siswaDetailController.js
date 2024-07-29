angular
    .module("siswaDetailController", [])
    .controller(
        "siswaDetailController",
        function ($scope, siswaService, paketService, helperService) {
            var path = window.location.href;
            var pathData = path.split("/");
            var id = pathData[5];
            $("#contentx").css("display", "block");
            $scope.penilaian = {};
            siswaService.getById(id).then(
                (x) => {
                    $scope.siswa = x;
                    if (!$scope.siswa.sertifikat) {
                        var sertifikat = { id: 0 };
                        sertifikat.instansi =
                            $scope.siswa.paket.eksternal.instansi;
                        sertifikat.siswa_id = x.id;
                        sertifikat.nomor = "";
                        sertifikat.nomorseri = "";
                        sertifikat.diambiloleh = "";
                        sertifikat.ketuapenguji =
                            $scope.siswa.paket.eksternal.nama;
                        sertifikat.tanggalpenetapan = null;
                        sertifikat.tanggalcetak = null;
                        sertifikat.tanggalambil = null;
                        $scope.siswa.sertifikat = sertifikat;
                    } else {
                        $scope.siswa.sertifikat.tanggalpenetapan = new Date(
                            $scope.siswa.sertifikat.tanggalpenetapan
                        );
                        $scope.siswa.sertifikat.tanggalcetak =
                            helperService.toDate(
                                $scope.siswa.sertifikat.tanggalcetak
                            );
                        $scope.siswa.sertifikat.tanggalambil =
                            $scope.siswa.sertifikat.tanggalambil == null
                                ? null
                                : new Date(
                                      $scope.siswa.sertifikat.tanggalambil
                                  );
                    }

                    paketService.getById(x.paket_id).then(
                        (x) => {
                            $scope.pakets = x;
                            $scope.pakets.kompetensis.forEach((element) => {
                                var komp = $scope.siswa.penilaian.find(
                                    (x) => x.kompetensi_id == element.id
                                );
                                if (!komp) {
                                    $scope.siswa.penilaian.push({
                                        id: 0,
                                        nilai: 0,
                                        kompeten: 0,
                                        kompetensi_id: element.id,
                                        siswa_id: $scope.siswa.id,
                                        kompetensi: element,
                                    });
                                }
                            });
                        },
                        (err) => {}
                    );
                },
                (err) => {}
            );

            $scope.add = () => {
                $scope.siswa.kompetensis.push({
                    id: 0,
                    siswa_id: $scope.siswa.id,
                    kode: "",
                    elemen: "",
                });
            };

            $scope.delete = (obj) => {
                var index = $scope.siswa.indexOf(obj);
                $scope.siswa.splice(index, 1);
            };

            $scope.save = () => {
                siswaService.put($scope.siswa).then(
                    (x) => {
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
            };

            $scope.updateSertifikat = (sertifikat) => {
                siswaService.updateSertifikat(sertifikat).then(
                    (x) => {
                        sertifikat.id = x.id;
                        canPrint();
                        Swal.fire({
                            title: "Tersimpan!",
                            text: "Data berhasil disimpan.",
                            icon: "success",
                        });
                    },
                    (err) => {
                        Swal.fire({
                            title: "Error!",
                            text: err.data.message,
                            icon: "error",
                        });
                    }
                );
            };

            $scope.showSertifikatPanel = () => {
                if (
                    ($scope.siswa != null &&
                        $scope.siswa.penilaian != null &&
                        $scope.rata2Penilaian($scope.siswa.penilaian) > 0) || $scope.getKompeten($scope.siswa.penilaian)
                ) {
                    return true;
                }
                return false;
            };

            $scope.rata2Penilaian = (penilaian) => {
                if (penilaian) {
                    var rata2 = penilaian.reduce((x, i) => {
                        return x + i.nilai;
                    }, 0);
                    return ($scope.rata2 = rata2 / penilaian.length);
                }
                return 0;
            };

            $scope.canPrint = () => {
                if (
                    $scope.siswa != null &&
                    $scope.siswa.penilaian != null &&
                    $scope.siswa.sertifikat
                ) {
                    var sertifikat = $scope.siswa.sertifikat;
                    if (
                        sertifikat.id &&
                        sertifikat.instansi &&
                        sertifikat.nomor &&
                        sertifikat.nomorseri
                    ) {
                        return true;
                    }
                }
                return false;
            };

            $scope.print = () => {
                window.print();
                window.onafterprint = function () {
                    $scope.siswa.sertifikat.tanggalcetak = new Date();
                    $scope.updateSertifikat($scope.siswa.sertifikat);
                };
            };

            $scope.penetapan = (date) => {
                return helperService.getPenetapan(date);
            };

            $scope.getKompeten = (penilaian) => {
                if (penilaian) {
                    return (penilaian.filter((x) => x.kompeten).length ==penilaian.length);
                }
                return false;
            };
        }
    );
