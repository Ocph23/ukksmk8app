@extends('admin')
@section('content')
<div ng-controller="siswaController">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">DATA SISWA</h4>
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
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th style="text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="group in datasiswa | groupBy:'jurusan_id'">
                        <tr class="bg-gradient-info">
                            <td colspan="7"><% group[0].jurusan.nama %></td>
                        </tr>
                        <tr ng-repeat="item in group">
                            <td><% $index+1 %></td>
                            <td><% item.nis %></td>
                            <td><% item.nama %></td>
                            <td><% item.jk %></td>
                            <td><% item.alamat %></td>
                            <td style="text-align:right">
                                <button ng-click="edit(item)" type="button" class="btn btn-gradient-warning btn-icon">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button ng-click="delete(item)" type="button" class="btn btn-gradient-danger btn-icon">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                                <a ng-href="/admin/siswa/<%item.id%>">
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
                                                <label>NIS </label>
                                                <input ng-model="model.nis" type="text" class="form-control" placeholder="NIS">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Siswa </label>
                                                <input ng-model="model.nama" type="text" class="form-control" placeholder="Nama Siswa">
                                            </div>
                                            <div class="form-group">
                                                <label >Jenis Kelamin</label>
                                                <select ng-model="model.jk" class="form-control" ng-options="item as item for item in genders"></select>
                                            </div>
                                            <div class="form-group">
                                                <label>Alamat </label>
                                                <input ng-model="model.alamat" type="text" class="form-control" placeholder="Alamat">
                                            </div>
                                            <div class="form-group">
                                                <label>Jurusan</label>
                                                <select ng-model="model.jurusan_id" ng-change="changeSelectedjurusan(model.jurusan_id)" class="form-control" 
                                                    ng-options="item.id as (item.nama+' ('+item.kode+') ') for item in dataJurusan"></select>
                                            </div>
                                            <div class="form-group">
                                                <label>Paket Uji Kompetensi</label>
                                                <select ng-model="model.paket_id" class="form-control" ng-options="item.id as (item.judultugas+' ('+item.kode+') ') for item in pakets"></select>
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