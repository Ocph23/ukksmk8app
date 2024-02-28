angular.module('siswaDetailController', [])
    .controller('siswaDetailController', function ($scope, $location, siswaService, paketService) {
        var path = $location.$$path;
        var pathData = path.split('/');
        var id = pathData[3];

        $scope.penilaian = {};
        siswaService.getById(id)
            .then(x => {
                $scope.siswa = x;
                paketService.getById(x.paket_id)
                    .then(x => {
                        $scope.pakets = x;
                        $scope.pakets.kompetensis.forEach(element => {
                            var komp = $scope.siswa.penilaian.find(x => x.kompetensi_id == element.id);
                            if (!komp) {
                                $scope.siswa.penilaian.push({
                                    id: 0,
                                    nilai: 0,
                                    kompeten: 0,
                                    kompetensi_id: element.id,
                                    siswa_id: $scope.siswa.id,
                                    kompetensi:element

                                })
                            }
                        });

                    },
                        err => { });



            },
                err => { });


        $scope.add = () => {
            $scope.siswa.kompetensis.push({ id: 0, siswa_id: $scope.siswa.id, kode: "", elemen: "" });

        }

        $scope.delete = (obj) => {
            var index = $scope.siswa.indexOf(obj);
            $scope.siswa.splice(index, 1);
        }

        $scope.save = () => {
            siswaService.put($scope.siswa)
                .then(x => {
                    Swal.fire({
                        title: "Tersimpan!",
                        text: "Data berhasil disimpan.",
                        icon: "success"
                    });

                }, err => {
                    Swal.fire({
                        title: "Error!",
                        text: err.message,
                        icon: "Error"
                    });

                 });


        }


    })
