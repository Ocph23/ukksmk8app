@extends('admin')
@section('content')
<div ng-controller="laporanController">
    <ng-template style="display: none;" id="content">
        <div class="noprint col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">DATA AKSESOR</h4>
                    <div style="display: flex; justify-content:space-between; align-items:center">
                        <div class="d-flex">
                            <div style="width: 100px;" class="form-group mr-2">
                                <label>Tahun Ajaran</label>
                                <select ng-model="ta" ng-change="searchAksesor(ta, jurusan)" class="form-control" ng-options="item as item.nama for item in tahunajaran" placeholder="Tipe Aksesor/Jenis"></select>
                            </div>
                        </div>

                        <button type="button" ng-click="print()" class="btn btn-inverse-info  btn-icon">
                            <i class="mdi mdi-printer"></i>
                        </button>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>JK</th>
                                <th>Jenis</th>
                                <th>Instansi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>

                        <tbody ng-repeat="group in data | groupBy:'jenis'">
                            <tr style="background-color:#e2ebf3;">
                                <td colspan="6" class="text-uppercase" ><% group[0].jenis %></td>
                            </tr>
                            <tr ng-repeat="item in group">
                                <td><% $index+1 %></td>
                                <td><% item.nama %></td>
                                <td><% item.jk %></td>
                                <td><% item.jenis %></td>
                                <td><% item.instansi %></td>
                                <td><% item.catatan %></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="justprint" style="padding: 20px;">
            <div class="title d-flex flex-column" style="align-items:center; justify-content:center">
                <h4 class="card-title text-uppercase"> Uji Kompetensi Keahlian Sekolah Menengah Kejuruan</h4>
                <h4 class="text-uppercase">DATA AKSESOR </h4>
                <h4 class="card-title">TAHUN AJARAN <% ta.nama %></h4>
            </div>
            <hr style="height: 0.5px;" />
            <table class="table mt-3" id="nilai">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Jenis</th>
                        <th>Instansi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody ng-repeat="group in data | groupBy:'jenis'">
                            <tr style="background-color:#e2ebf3;">
                                <td colspan="6" class="text-uppercase" ><% group[0].jenis %></td>
                            </tr>
                            <tr ng-repeat="item in group">
                                <td><% $index+1 %></td>
                                <td><% item.nama %></td>
                                <td><% item.jk %></td>
                                <td><% item.jenis %></td>
                                <td><% item.instansi %></td>
                                <td><% item.catatan %></td>
                            </tr>

                        </tbody>
            </table>
        </div>
        <style>
            #nilai tr td,
            #nilai tr th {
                border: 1px solid;
                padding: 0.4rem;
                background-color: transparent !important;
            }
        </style>
    </ng-template>
</div>

@stop