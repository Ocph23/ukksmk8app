angular
    .module("laporanController", [])
    .controller(
        "laporanController",
        function ($scope, laporanService, tahunajaranService, jurusanService) {
            $model = {};
            $scope.data = [];
            document.getElementById("content").style.display = "block";

            tahunajaranService.get().then(
                (result) => {
                    $scope.tahunajaran = result;
                    $scope.selectedTahunAjaran = result.find((x) => x.aktif);
                    jurusanService.get().then(
                        (resultJurusan) => {
                            $scope.dataJurusan = resultJurusan;
                        },
                        (err) => {}
                    );
                },
                (err) => {}
            );

            $scope.print =()=>{
               window.print();     
            }

            $scope.search = (ta, jurusan) => {
                if (ta && jurusan) {
                    laporanService.kelulusan(ta.id, jurusan.id).then(
                        (result) => {
                            $scope.data = result;
                        },
                        (err) => {

                        }
                    );
                }
            };
        }
    );
