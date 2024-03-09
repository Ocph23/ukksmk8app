angular.module('paketDetailController', [])
    .controller('paketDetailController', function ($scope,  paketService) {
        var path = window.location.href;
        var pathData = path.split('/');
        var id = pathData[5];
        document.getElementById("contentx").style.display = 'block';
        paketService.getById(id)
            .then(x => {
                $scope.paket = x;
            },
                err => { });


        $scope.add = () => {
            $scope.paket.kompetensis.push({id:0, paket_id:$scope.paket.id, kode: "", elemen: "" });

        }

        $scope.delete = (obj) => {
            var index = $scope.paket.kompetensis.indexOf(obj);
            $scope.paket.kompetensis.splice(index, 1);
        }

        $scope.save = () => {
            paketService.putDetail($scope.paket)
                .then(x => {
                    Swal.fire({
                        title: "Tersimpan!",
                        text: "Data berhasil disimpan.",
                        icon: "success"
                    });

                }, err => {
                    Swal.fire({
                        title: "Error ",
                        text: err.message,
                        icon: "error"
                    });

                 });


        }


    })
