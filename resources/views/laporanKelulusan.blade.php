@extends('admin')
@section('content')
<div ng-controller="laporanController">
    <ng-template style="display: none;" id="content">
        <div class="noprint col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">DATA laporan</h4>

                    <div style="display: flex; justify-content:space-between; align-items:center">
                        <div class="d-flex">
                            <div style="width: 100px;" class="form-group mr-2">
                                <label>Tahun Ajaran</label>
                                <select ng-model="ta" ng-change="search(ta, jurusan)" class="form-control" ng-options="item as item.nama for item in tahunajaran" placeholder="Tipe Aksesor/Jenis"></select>
                            </div>
                            <div class="form-group">
                                <label>Jurusan</label>
                                <select ng-model="jurusan" ng-change="search(ta, jurusan)" class="form-control" ng-options="item as item.nama for item in dataJurusan" placeholder="Tipe Aksesor/Jenis"></select>
                            </div>
                        </div>

                        <button  type="button" ng-click="print()" class="btn btn-inverse-info  btn-icon">
                            <i class="mdi mdi-printer"></i>
                        </button>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>JK</th>
                                <th>TTL</th>
                                <th>Paket</th>
                                <th style="text-align:center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in data">
                                <td><% $index+1 %></td>
                                <td><% item.nis %></td>
                                <td><% item.nama %></td>
                                <td><% item.jk %></td>
                                <td><% item.ttl %></td>
                                <td><% item.paket %></td>
                                <td style="text-align:center"><% item.status %></td>
                            </tr>

                        </tbody>
                    </table>

                    <!-- Button trigger modal -->

                    <!-- Modal -->
                </div>
            </div>
        </div>
    </ng-template>

</div>

@stop