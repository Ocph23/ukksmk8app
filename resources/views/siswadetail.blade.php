@extends('admin')
@section('content')
<div ng-controller="siswaDetailController">
    <ng-template style="display: none;" id="contentx">
        <div class="noprint">
            <div class="row ">
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
                                            <td><input type="text8" ng-model="siswa.sertifikat.diambiloleh" type="text" class="form-control"> </td>
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
                            <div class="table-responsive" ng-if="siswa.penilaian">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;"> Kode </th>
                                            <th> Kompetensi </th>
                                            <th style="width: 75px;">Nilai</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in siswa.penilaian">
                                            <td> <% item.kompetensi.kode %> </td>
                                            <td> <% item.kompetensi.elemen %> </td>
                                            <td ng-if="siswa.paket.basisnilai"> 
                                                {{ item.nilai }}
                                                <input type="number" max="100" ng-change="rata2Penilaian(siswa.penilaian)" class="form-control" ng-model="item.nilai"> </td>
                                            <td ng-if="!siswa.paket.basisnilai" style="text-align:center "> <input style="font-size:12px" type="checkbox" ng-model="item.kompeten"> </td>

                                        </tr>
                                        <tr style="background-color: #ebebeb" ng-if="siswa.paket.basisnilai">
                                            <td colspan="2"> Nilai Rata-Rata </td>
                                            <td style="width: 150px"> <input readonly type="number" class="form-control" value="<% rata2.toFixed(2) %>"> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ng-template>

    <div ng-if="siswa.penilaian" class="justprint" style="height:100vh; width: 100%; padding:90px; background-size:100% 100%; background-image:url('/assets/images/certificate2.jpg')">
        <div style="width: 100%; display: flex; align-items:center; justify-content:space-between">
            <img style="width: 10%; height:20%" src="/assets/images/LOGO_SMKN8.png">
            <div style="display:flex; justify-content:center; align-items: center; flex-direction:column">
                <h2 style="line-height: 15px;">PEMERINTAH KOTA JAYAPURA</h2>
                <h4 line-height: 15px;>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
                <h3 style="font-size: 18px;"><b style="color: #6696fc;">SMK NEGERI 8 TEKNOLOGI INFORMASI DAN KOMUNIKASI KOTA JAYAPURA</b></h3>

                <p class="text-capitalize" style="line-height: 5px;">
                    JL. gelanggang II RT 04 RW 01 kelurahan waena, distrik heram, kota jayapura, kode pos 99358
                </p>
                <p style="line-height: 5px;">
                    <i class="mdi mdi-phone-outline"></i> (0967) 5170108;
                    <i class="mdi mdi-email-outline"></i> smkn8.kotajayapura@gmail.com;
                    <i class="mdi mdi-web"></i> https://smkn8tikjayapura.sch.id
                </p>
            </div>
            <img ng-if="siswa.paket.eksternal.logo" style="width: 10%; height:30%" src="/storage/instansi/<%siswa.paket.eksternal.logo%>">
        </div>
        <hr />
        <div style=" display: flex; flex-direction:column">
            <label class="text-right">Nomor Seri : <%siswa.sertifikat.nomorseri%></label>
            <div class="text-center">
                <h3 class="font-italic" style="text-decoration: underline; line-height:15px"><b>SERTIFIKAT KOMPETENSI</b></h3>
                <h4>Certificate Of Competency</h4>
                <h4>Nomor : <%siswa.sertifikat.nomor%></h4>
            </div>
            <div style="padding:10px 30px">
                <p>Sertifikasi ini diselenggaranan berdasarkan Pedoman Penyelenggaraan Uji Kompetensi
                    Keahlian Sekolah Menengah Kejuruan Tahun <% siswa.sertifikat.tanggalpenetapan.getFullYear() %></p>
                <h4 class="text-center">Menyatakan Bahwa : </h4>
                <table>
                    <tr>
                        <td style="width: 200px;">Nama</td>
                        <td>: <% siswa.nama %></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>: <% siswa.tempatlahir %>,  <% penetapan(siswa.tanggallahir) %> </td>
                    </tr>
                    <tr>
                        <td>Sekolah</td>
                        <td>: SMK Negeri 8 Teknologi Informasi dan Komunikasi Kota Jayapura</td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>: <% siswa.jurusan.nama %></td>
                    </tr>
                </table>
                <p class="mt-2">Dinyatakan <span class="font-italic"><b>LULUS</b></span> Uji Kompetensi Kejuruan dan diakui telah memiliki kompetensi seperti tercantum di balik sertifikat ini</p>
            </div>

            <div style="display: flex; justify-content:space-between; padding:10px 80px">
                <div style="display: flex; flex-direction:column">
                    <label>Mengetahui,</label>
                    <label style="line-height:2px">Kepala Sekolah</label>
                    <label style="margin-top: 60px;" class="font-weight-bold"><u>Feronika Munthe, S.Pd. , M.Pd.</u></label>
                    <label style="line-height:2px">NIP : 19780713 200502 2009</label>
                </div>

                <div style="display: flex; flex-direction:column">
                    <label>Jayapura, <% penetapan(siswa.sertifikat.tanggalpenetapan) %></label>
                    <label style="line-height:2px">Ketua Tim Penguji</label>
                    <label style="margin-top: 60px;" class="font-weight-bold"><u> <%siswa.paket.eksternal.nama%></u></label>
                    <label style="line-height:2px"><%siswa.paket.eksternal.instansi%></label>
                </div>
            </div>
        </div>

        <style>
            hr {
                margin: 0;
                margin-bottom: 5px;
                border: 2px solid;
                border-radius: 0px;
            }
        </style>
    </div>

    <div ng-if="siswa.penilaian" class="justprint" style="height:100vh; width: 100%; padding:100px 70px; background-size:100% 100%; background-image:url('/assets/images/certificate2.jpg')">
        <div style="width: 100%; display: flex; align-items:center; justify-content:center">
            <div style="display:flex; justify-content:center; align-items: center; flex-direction:column">
                <h3 class="text-uppercase">kompetensi keahlian <% siswa.jurusan.nama %> </h3>
                <h3 class="text-uppercase">daftar kompetensi/subkompetensi </h3>
            </div>
        </div>
        <div style="display: flex; flex-direction:column">
            <div style="padding:20px">
                <h4 class="text-left mb-3"><b>Tugas : <% siswa.paket.kode %> - <% siswa.paket.judultugas %></b> </h4>
                <table class="table" id="nilai">
                    <thead>
                        <tr style="height: 35px;">
                            <th style="width: 50px;  vertical-align: baseline;"> No. </th>
                            <th style="width: 150px;"> Kode </th>
                            <th> Kompetensi </th>
                            <th style="width: 100px;">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in siswa.penilaian">
                            <td class="text-center"> <% $index+1 %> </td>
                            <td> <% item.kompetensi.kode %> </td>
                            <td> <% item.kompetensi.elemen %> </td>
                            <td ng-if="siswa.paket.basisnilai" style="width: 100px; text-align:center "> <% item.nilai %> </td>
                            <td ng-if="!siswa.paket.basisnilai" style="width: 100px; text-align:center ">
                                <i style="font-size: 16px;" ng-if="item.kompeten" class="mdi mdi-checkbox-marked-outline"></i>
                                <i style="font-size: 16px;" ng-if="!item.kompeten" class="mdi mdi-checkbox-blank-outline"></i>
                            </td>


                        </tr>
                        <tr ng-if="siswa.paket.basisnilai">
                            <td class="text-center" style="height: 35px;" colspan="3"> <b>Nilai Rata-Rata</b> </td>
                            <td style="width: 100px; text-align:center "> <b><% rata2.toFixed(2) %></b> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <style>
            hr {
                margin: 0;
                margin-bottom: 5px;
                border: 2px solid;
                border-radius: 0px;
            }

            th {
                vertical-align: middle !important;
                text-align: center;
            }

            #nilai tr td,
            #nilai tr th {
                border: 1px solid;
                padding: 0.4rem;
                background-color: transparent !important;
            }
        </style>
    </div>
</div>
@stop