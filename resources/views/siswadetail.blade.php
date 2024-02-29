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
                                        <th style="width: 200px;"> Kode </th>
                                        <th> Kompetensi </th>
                                        <th style="width: 75px;">Nilai</th>
                                        <th style="width: 75px;">Kompeten</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in siswa.penilaian">
                                        <td> <% item.kompetensi.kode %> </td>
                                        <td> <% item.kompetensi.elemen %> </td>
                                        <td style="width: 150px"> <input type="number" class="form-control" ng-model="item.nilai"> </td>
                                        <td style="width: 100px; "> <input style="font-size:12px" type="checkbox" ng-model="item.kompeten"> </td>
                                       
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