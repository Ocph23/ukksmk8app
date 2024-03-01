@extends('admin')
@section('content')
<div ng-controller="siswaDetailController">
    <ng-template style="display: none;" id="content">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Biodata Siswa</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>NIS </td>
                                        <td> <% siswa.nis %> </td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td> <% siswa.nama %> </td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td> <% siswa.jk %> </td>
                                    </tr>
                                    <tr>
                                        <td>Tempat, Tanggal Lahir</td>
                                        <td> <% siswa.tempatlahir %>, <% siswa.tanggallahir %> </td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td> <% siswa.alamat %> </td>
                                    </tr>
                                    <tr>
                                        <td>Jurusan</td>
                                        <td> <% siswa.jurusan.kode %> - <% siswa.jurusan.nama %> </td>
                                    </tr>
                                    <tr>
                                        <td>Paket Uji Kompetensi</td>
                                        <td> <% siswa.paket.kode %> - <% siswa.paket.judultugas %> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-if="showSertifikatPanel()" class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; justify-content:space-between">
                            <h4 class="card-title">Ujian Komptetensi Dan Sertifikat</h4>
                            <div>
                                <button type="button" ng-click="updateSertifikat(siswa.sertifikat)" class="btn btn-inverse-primary btn-icon">
                                    <i class="mdi mdi-content-save-all"></i>
                                </button>
                                <button ng-if="canPrint()" type="button" ng-click="print()" class="btn btn-inverse-info  btn-icon">
                                    <i class="mdi mdi-printer"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Instansi Penguji </td>
                                        <td><input ng-model="siswa.sertifikat.instansi" type="text" class="form-control"> </td>
                                    </tr>
                                    <tr>
                                        <td>Ketua Penguji </td>
                                        <td><input ng-model="siswa.sertifikat.ketuapenguji" type="text" class="form-control"> </td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Seri Sertifikat</td>
                                        <td><input ng-model="siswa.sertifikat.nomorseri" type="text" class="form-control"> </td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Serifikat</td>
                                        <td><input ng-model="siswa.sertifikat.nomor" type="text" class="form-control"> </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Ditetapkan</td>
                                        <td><input type="date" ng-model="siswa.sertifikat.tanggalpenetapan" type="text" class="form-control"> </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Dicetak</td>
                                        <td> <span class="ml-3"><% siswa.sertifikat.tanggalcetak %></span> </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Ambil</td>
                                        <td><input type="date" ng-model="siswa.sertifikat.tanggalambil" type="text" class="form-control"> </td>
                                    </tr>
                                    <tr>
                                        <td>Diambil Oleh</td>
                                        <td><input type="text" ng-model="siswa.sertifikat.diambiloleh" type="text" class="form-control"> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; justify-content:space-between">
                            <h4 class="card-title">ELEMEN KOMPETENSI</h4>
                            <div>
                                <button type="button" ng-click="save()" class="btn btn-inverse-primary btn-icon">
                                    <i class="mdi mdi-content-save-all"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;"> Kode </th>
                                        <th> Kompetensi </th>
                                        <th style="width: 75px;">Nilai</th>
                                        <th style="width: 40px; text-align:center">Kompeten</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in siswa.penilaian">
                                        <td> <% item.kompetensi.kode %> </td>
                                        <td> <% item.kompetensi.elemen %> </td>
                                        <td style="width: 150px"> <input type="number" ng-change="rata2Penilaian(siswa.penilaian)" class="form-control" ng-model="item.nilai"> </td>
                                        <td style="width: 100px; text-align:center "> <input style="font-size:12px" type="checkbox" ng-model="item.kompeten"> </td>

                                    </tr>
                                    <tr style="background-color: #ebebeb">
                                        <td colspan="1"> Nilai Rata-Rata </td>
                                        <td style="width: 150px">
                                        <td style="width: 150px"> <input type="number" class="form-control" value="<% rata2 %>"> </td>
                                        </td>
                                        <td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </ng-template>
</div>

@stop