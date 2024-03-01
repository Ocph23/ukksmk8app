@extends('admin')
@section('content')
<div ng-controller="jurusanController">
    <ng-template style="display: none;" id="content">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">DATA JURUSAN</h4>
                    <button ng-click="tambah()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah
                    </button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Nama Jurusan</th>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                <th style="text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in data">
                                <td><% $index+1 %></td>
                                <td><% item.nama %></td>
                                <td><% item.kode %></td>
                                <td><% item.deskripsi %></td>
                                <td style="text-align:right">
                                    <button ng-click="edit(item)" type="button" class="btn btn-inverse-warning btn-icon">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button ng-click="delete(item)" type="button" class="btn btn-inverse-danger btn-icon">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
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
                                            <h4 class="card-title">Tahun Ajaran</h4>
                                            <form class="forms-sample">
                                                <div class="form-group">
                                                    <label for="exampleInputUsername1">Nama Jurusan</label>
                                                    <input ng-model="model.nama" type="text" class="form-control" placeholder="Jurusan">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputUsername1">Kode</label>
                                                    <input ng-model="model.kode" type="text" class="form-control" placeholder="Kode">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleTextarea1">Deskripsi</label>
                                                    <textarea ng-model="model.deskripsi" class="form-control" rows="4"></textarea>
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
    </ng-template>

</div>

@stop