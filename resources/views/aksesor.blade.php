@extends('admin')
@section('content')
<div ng-controller="aksesorController">
    <ng-template style="display: none;" id="content">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">DATA AKSESOR</h4>
                    <button ng-click="tambah()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah
                    </button>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tipe Aksesor</th>
                                <th>Instansi</th>
                                <th>Logo Instansi</th>
                                <th style="text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in data">
                                <td><% $index+1 %></td>
                                <td><% item.nama %></td>
                                <td><% item.jk %></td>
                                <td><% item.jenis %></td>
                                <td><% item.instansi %></td>
                                <td>
                                    <button ng-click="showLogo(item.logo)" ng-if="item.logo" type="button" class="btn btn-gradient-success btn-sm">View</button>
                                </td>
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
                        <div class="modal-dialog  modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Aksesor</h4>
                                            <form class="forms-sample">
                                                <div class="form-group">
                                                    <label>Nama </label>
                                                    <input ng-model="model.nama" type="text" class="form-control" placeholder="Nama">
                                                </div>
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <select ng-model="model.jk" class="form-control" ng-options="item as item for item in genders"></select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tipe Aksesor/Jenis</label>
                                                    <select ng-model="model.jenis" class="form-control" ng-options="item as item for item in aksesorsTypes" placeholder="Tipe Aksesor/Jenis"></select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Instansi/Asal</label>
                                                    <input ng-model="model.instansi" type="text" class="form-control" placeholder="Instansi/Asal">
                                                </div>
                                                <div class="form-group">
                                                    <label>Logo Instansi</label>
                                                    <input type="file" file-model="model.file" class="form-control" name="fileToUpload" id="fileToUpload">
                                                </div>

                                                <div class="form-group">
                                                    <label>Catatan</label>
                                                    <textarea ng-model="model.catatan" class="form-control" rows="4"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Keluar</button>
                                    <button type="button" ng-click="simpan(model)" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="logoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <img style="width: 100%;" src="/storage/instansi/<%logoInstansi%>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Keluar</button>
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