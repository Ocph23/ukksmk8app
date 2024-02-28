@extends('admin')
@section('content')
<div ng-controller="paketDetailController">
    <div class="row">


        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">PAKET UJI KOMPETENSI</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Kode </td>
                                    <td> <% paket.kode %> </td>
                                </tr>
                                <tr>
                                    <td>Jurusan</td>
                                    <td> <% paket.jurusan.nama %> </td>
                                </tr>
                                <tr>
                                    <td>Alokasi Waktu</td>
                                    <td> <% paket.alokasiwaktu %> </td>
                                </tr>
                                <tr>
                                    <td>Bentuk Penugasan</td>
                                    <td> <% paket.bentukpenugasan %> </td>
                                </tr>
                                <tr>
                                    <td>Judul</td>
                                    <td> <% paket.judultugas %> </td>
                                </tr>
                                <tr>
                                    <td>Aksesor Internal</td>
                                    <td> <% paket.internal.nama %> </td>
                                </tr>
                                <tr>
                                    <td>Aksesor Eksternal</td>
                                    <td> <% paket.eksternal.nama %> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content:space-between">
                        <h4 class="card-title">ELEMEN KOMPETENSI</h4>
                        <div>
                            <button type="button" ng-click="add()" class="btn btn-inverse-success btn-icon">
                                <i class="mdi mdi-plus"></i>
                            </button>
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
                                    <th> Elemen Kompetensi </th>
                                    <th style="width: 75px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in paket.kompetensis">
                                    <td> <input type="text" class="form-control" ng-model="item.kode"> </td>
                                    <td> <input type="text" class="form-control" ng-model="item.elemen"> </td>
                                    <td>
                                        <button ng-click="delete(item)" type="button" class="btn btn-gradient-danger btn-icon">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop