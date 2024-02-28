angular.module('paketDetailController', [])
    .controller('paketDetailController', function ($scope, $location, paketService) {
        var path = $location.$$path;
        var pathData = path.split('/');
        var id = pathData[3];

        paketService.getById(id)
            .then(x => {
                $scope.paket = x;
            },
                err => { });


        $scope.add = () => {
            $scope.paket.kompetensis.push({id:0, paket_id:$scope.paket.id, kode: "", elemen: "" });

        }

        $scope.delete = (obj) => {
            var index = $scope.paket.indexOf(obj);
            $scope.paket.splice(index, 1);
        }

        $scope.save = () => {
            paketService.put($scope.paket)
                .then(x => {
                    Swal.fire({
                        title: "Tersimpan!",
                        text: "Data berhasil disimpan.",
                        icon: "success"
                    });

                }, err => { });


        }


    })
