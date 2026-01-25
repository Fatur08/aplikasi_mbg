@extends('layouts.owner.tabler')
@section('content')
<style>
/* === Section Info Dapur === */
.section-info {
    margin-top: 40px;
    margin-bottom: 25px;
    text-align: center;
}
.info-card {
    display: inline-block;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    padding: 25px 40px;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}
.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.info-card h4 {
    color: #111827;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 20px;
}
.info-card p {
    color: #6b7280;
    margin: 0;
    font-size: 18px;
}

/* === Table Style === */
.custom-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    background-color: #ffffff;
}

.custom-table thead th {
    background: linear-gradient(135deg, #007bff, #00bcd4);
    color: white;
    text-align: center;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.5px;
    padding: 12px;
    border: none;
}

.custom-table thead tr:first-child th {
    background: linear-gradient(135deg, #0069d9, #17a2b8);
    font-size: 17px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.custom-table tbody td, 
.custom-table tbody th {
    padding: 12px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    font-size: 16px;
    color: #333;
}

.custom-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.custom-table tbody tr:hover {
    background-color: #e9f5ff;
    transition: 0.3s;
}

.table-container {
    max-width: 1600px;
}

/* === Buttons === */
.btn-status {
    font-size: 13px;
    padding: 4px 14px;
    border-radius: 20px;
    font-weight: 600;
    border: none;
    color: #fff;
}
.btn-menunggu {
    background-color: #facc15;
    color: #111827;
}
.btn-validasi {
    background-color: #38bdf8;
}
.btn-menunggu:hover {
    background-color: #eab308;
}
.btn-validasi:hover {
    background-color: #0ea5e9;
}

/* === Responsive === */
@media (max-width: 768px) {
    .info-card {
        width: 100%;
        padding: 20px;
    }
    .info-card h4 {
        font-size: 18px;
    }
    .table-modern {
        font-size: 13px;
    }
}
</style>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <div class="page-pretitle">
                                    Halaman
                                </div>
                                <h2 class="page-title">
                                    Laporan Keuangan
                                </h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/owner/laporan/keuangan" method="GET" id="FormLaporanKeuangan">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="input-icon">
                                                    <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                        <option value="">Pilih Dapur</option>
                                                        @foreach($dapurList as $dapur)
                                                            <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-4">
                                                <div class="input-icon">
                                                    <select name="pilih_supplier_koperasi" id="pilih_supplier_koperasi" class="form-select">
                                                        <option value="">Pilih Supplier / Koperasi</option>
                                                        <option value="Supplier" {{ request('pilih_supplier_koperasi') == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                                                        <option value="Koperasi" {{ request('pilih_supplier_koperasi') == 'Koperasi' ? 'selected' : '' }}>Koperasi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                        Cari    
                                                    </button>
                                                </div>
                                            </div>
                                    </form>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-success w-100" id="cetak_laporan_keuangan" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                        Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="row mt-2 table-container">
                                <div class="col-12">
                                    @if(!$sudahCari)
                                        <div class="alert alert-info text-center">
                                            Silakan lakukan pencarian terlebih dahulu
                                        </div>
                                    @elseif($dataKosong)
                                        <div class="alert alert-warning text-center">
                                            Data tidak ditemukan
                                        </div>
                                    @else
                                        <div style="width: 100%; max-width: 1100px; margin: 0 auto;">
                                            <canvas id="koperasiChartOwner" height="340"></canvas>
                                        </div>
                                        
                                        <div class="table-wrapper">
                                            <div class="table-responsive">
                                                <table class="table custom-table">
                                                    <thead class="table-primary text-center">
                                                        <tr>
                                                            <th style="text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                                                            <th style="text-align: center; vertical-align: middle;" rowspan="2">Tanggal</th>
                                                            <th style="text-align: center; vertical-align: middle;" rowspan="2">Barang</th>
                                                            <th style="text-align: center; vertical-align: middle;" colspan="2">Keterangan</th>
                                                            <th style="text-align: center; vertical-align: middle;" rowspan="2">Dana</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="text-align: center; vertical-align: middle;">Koperasi</th>
                                                            <th style="text-align: center; vertical-align: middle;">Supplier</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $i => $d)
                                                        <tr>
                                                            <td class="text-center">{{ $i + 1 }}</td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::parse($d->tanggal_laporan_keuangan)->translatedFormat('d F Y') }}
                                                            </td>

                                                            <td class="text-center">
                                                                <a href="#" class="barang_laporan_keuangan btn btn-info btn-sm"
                                                                   data-id="{{ $d->id_data_koperasi }}">
                                                                    👁 Lihat
                                                                </a>
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($d->dari_koperasi)
                                                                    ✅
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($d->dari_supplier)
                                                                    ✅
                                                                @endif
                                                            </td>

                                                            <td>
                                                                Rp. {{ number_format($d->total_harga, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Modal Lihat Barang Modal Keluar --}}
<div class="modal modal-blur fade" id="modal-lihatbaranglaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Barang Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbaranglaporankeuangan">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $(".barang_laporan_keuangan").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/keuangan/barang_laporan_keuangan',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatbaranglaporankeuangan").html(respond);
                }
            });
            $("#modal-lihatbaranglaporankeuangan").modal("show");
        });


        $("#FormLaporanKeuangan").submit(function(){
            var pilih_dapur    = $("#pilih_dapur").val();
            var dari_tanggal   = $("#dari_tanggal").val();
            var sampai_tanggal = $("#sampai_tanggal").val();
            if(pilih_dapur==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dapur Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#pilih_dapur").focus();
                  });
                return false;
            } else if (dari_tanggal==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dari Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#dari_tanggal").focus();
                  });
                return false;
            } else if (sampai_tanggal==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Sampai Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#sampai_tanggal").focus();
                  });
                return false;
            }
        });


        $("#cetak_laporan_keuangan").click(function(e){
            e.preventDefault(); // Mencegah langsung pindah halaman
                
            var pilih_dapur                = $("#pilih_dapur").val();
            var dari_tanggal               = $("#dari_tanggal").val();
            var sampai_tanggal             = $("#sampai_tanggal").val();
            var pilih_supplier_koperasi    = $("#pilih_supplier_koperasi").val();
                
            if(pilih_dapur == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dapur Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#pilih_dapur").focus();
                });
                return false;
            
            } else if(dari_tanggal == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dari Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#dari_tanggal").focus();
                });
                return false;
            } else if(sampai_tanggal == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Sampai Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#sampai_tanggal").focus();
                });
                return false;
            } else if(pilih_supplier_koperasi == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Koperasi/Supplier Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#pilih_supplier_koperasi").focus();
                });
                return false;
            }
        
            // ✅ JIKA SUDAH LENGKAP, BARU BUKA HALAMAN CETAK
            let url = `/owner/laporan/keuangan/cetak_laporan_keuangan?pilih_dapur=${pilih_dapur}&dari_tanggal=${dari_tanggal}&sampai_tanggal=${sampai_tanggal}&pilih_supplier_koperasi=${pilih_supplier_koperasi}`;
            window.open(url, '_blank');
        });



        flatpickr("#dari_tanggal", {
            altInput: true,
            altFormat: "d F Y",      // 15 September 2025
            dateFormat: "Y-m-d",     // dikirim ke backend
            locale: "id",
            allowInput: true
        });
        
        flatpickr("#sampai_tanggal", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });
    });








    // === BAGIAN DIAGRAM BATANG ===
    let koperasiData = @json($grafik);
    
    if (!koperasiData || koperasiData.length === 0) {
        console.warn('DATA GRAFIK KOSONG');
    }
    
    const labels = koperasiData.map(item =>
        item.tanggal_laporan_keuangan ?? 'Tidak Ada Tanggal'
    );
    
    const modalKeluar = koperasiData.map(item =>
        Number(item.total_pengeluaran) || 0
    );
    
    // FORMAT RUPIAH
    function formatRupiah(angka) {
        return 'Rp.' + angka.toLocaleString('id-ID');
    }
    
    const canvas = document.getElementById('koperasiChartOwner');
    
    if (canvas) {
        const ctxBar = canvas.getContext('2d');
    
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pengeluaran',
                    data: modalKeluar,
                    backgroundColor: 'rgba(255, 0, 0, 0.7)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { font: { size: 14 } }
                    },
                    datalabels: {
                        display: true,
                        anchor: 'end',   // nempel di ujung atas batang
                        align: 'end',    // arah ke atas
                        offset: -4,      // naik sedikit biar tidak nempel batang
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: function (value) {
                            return value > 0
                                ? 'Rp.' + value.toLocaleString('id-ID')
                                : '';
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return formatRupiah(value);
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush