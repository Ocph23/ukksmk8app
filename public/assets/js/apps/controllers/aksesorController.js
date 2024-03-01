angular.module('aksesorController', [])
    .controller('aksesorController', function ($scope, $http, aksesorService, helperService) {
        document.getElementById("content").style.display = 'block';
        $scope.data = [];
        $scope.genders = helperService.getGender();
        $scope.aksesorsTypes = helperService.getAccesorTypes();
        aksesorService.get()
            .then(result => {
                $scope.data = result;
            },
                err => { });

        $scope.tambah = () => {
            $scope.model = {}
            $scope.model.jk = "Pria";
            $scope.model.jenis = "Internal";
        };

        $scope.edit = (item) => {
            $scope.model = JSON.parse(JSON.stringify(item));
            $('#exampleModal').modal('show')
        };

        function getBase64(file) {
            let myPromise = new Promise(function (resolve, reject) {
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    resolve(reader.result);
                };
                reader.onerror = function (error) {
                    reject();
                };
            });
            return myPromise;
        }

        $scope.simpan = async (param) => {

            if (param.file) {
                var data = await getBase64(param.file);
                if (data) {
                    param.dataLogo = data.split(',')[1];
                }
            }
            if (!param.id) {
                aksesorService.post(param)
                    .then(result => {
                        $scope.data.push(result);
                        param.id = result.id;
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
                aksesorService.put(param)
                    .then(result => {
                        var exsistData = $scope.data.find(x => x.id == param.id);
                        if (exsistData) {
                            exsistData.nama = result.nama;
                            exsistData.jk = result.jk;
                            exsistData.jenis = result.jenis;
                            exsistData.instansi = result.instansi;
                            exsistData.catatan = result.catatan;
                            exsistData.logo = result.logo;
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
                        url: '/api/aksesor/' + data.id,
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

        $scope.showLogo = (logo) => {
            $scope.logoInstansi = logo;
            $('#logoModal').modal('show')
        }

    })
