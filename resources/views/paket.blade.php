@extends('admin')
@section('content')
<div ng-controller="paketController">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">DATA PAKET UJI KOMPETENSI</h4>
                <div style="display:flex;align-items:center; justify-content:space-between">
                    <div class="form-group">
                        <label>Tahun Ajaran/Jenis</label>
                        <select ng-model="selectedTahunAjaran" ng-change="changeSelectedTahunAjaran(selectedTahunAjaran)" class="form-control" ng-options="item as item.nama for item in tahunajaran" placeholder="Tipe Aksesor/Jenis"></select>
                    </div>
                    <button ng-if="selectedTahunAjaran" ng-click="tambah()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah
                    </button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Bentuk Penugasan</th>
                            <th>Judul</th>
                            <th style="text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="group in dataPaket | groupBy:'jurusan_id'">
                        <tr class="bg-gradient-info">
                            <td colspan="7"><% group[0].jurusan.nama %></td>
                        </tr>
                        <tr ng-repeat="item in group">
                            <td><% $index+1 %></td>
                            <td><% item.kode %></td>
                            <td><% item.alokasiwaktu %></td>
                            <td><% item.bentukpenugasan %></td>
                            <td><% item.judultugas %></td>
                            <td style="text-align:right">
                                <button ng-click="edit(item)" type="button" class="btn btn-gradient-warning btn-icon">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button ng-click="delete(item)" type="button" class="btn btn-gradient-danger btn-icon">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                                <a ng-href="/admin/paket/<%item.id%>">
                                    <button type="button" class="btn btn-gradient-danger btn-icon">
                                        <i class="mdi mdi-format-list-bulleted"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <!-- Button trigger modal -->

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Paket</h4>
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label>Jurusan</label>
                                                <select ng-model="model.jurusan_id" class="form-control" ng-options="item.id as (item.nama+' ('+item.kode+') ') for item in dataJurusan"></select>
                                            </div>
                                            <div class="form-group">
                                                <label>Kode </label>
                                                <input ng-model="model.kode" type="text" class="form-control" placeholder="Kode">
                                            </div>
                                            <div class="form-group">
                                                <label>Judul Tugas </label>
                                                <input ng-model="model.judultugas" type="text" class="form-control" placeholder="Judul Tugas">
                                            </div>
                                            <div class="form-group">
                                                <label>Alokasi Waktu </label>
                                                <input ng-model="model.alokasiwaktu" type="text" class="form-control" placeholder="Alokasi Waktu">
                                            </div>
                                            <div class="form-group">
                                                <label>Bentuk Tugas </label>
                                                <input ng-model="model.bentukpenugasan" type="text" class="form-control" placeholder="Bentuk Tugas">
                                            </div>

                                            <div class="form-group">
                                                <label>Aksesor Internal</label>
                                                <select ng-model="model.aksesorinternal" class="form-control" ng-options="item.id as item.nama for item in aksesorInternal"></select>
                                            </div>
                                            <div class="form-group">
                                                <label>Aksesor Eksternal</label>
                                                <select ng-model="model.aksesoreksternal" class="form-control" ng-options="item.id as item.nama for item in aksesorEksternal"></select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                                <button type="button" ng-click="simpan(model)" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@stop