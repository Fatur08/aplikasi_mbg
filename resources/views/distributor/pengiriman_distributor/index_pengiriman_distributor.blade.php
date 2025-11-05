@extends('layouts.distributor.layout')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="pageTitle">Halaman Konfirmasi Pengiriman</div>
    <div class="right"></div>
</div>
@endsection
@section('content')
<div class="row" style="margin-top:4rem">
    <div class="col">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if(Session::get('success'))
            <div class="alert alert-success">
                {{ $messagesuccess }}
            </div>
        @endif
        @if(Session::get('error'))
            <div class="alert alert-error">
                {{ $messageerror }}
            </div>
        @endif
    </div>
</div>
<!-- App Capsule -->
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <h3>
            Daftar Distribusi Hari Ini <br>
            {{ $tanggalSekarang }}
        </h3>
    </div>
</div>

<div class="row mt-2 mb-5">
    <div class="col">
        @foreach ($distribusi as $data)
            <div class="distribution-card">
                <div class="row">
                    <div class="col">
                        <div class="section-sekolah">
                            Sekolah
                            <div class="value">{{ $data->tujuan_distribusi ?? '-' }}</div>
                        </div>
                        <div class="section-menu">
                            Menu
                            <div class="value">{{ $data->menu_makanan ?? '-' }}</div>
                        </div>
                        <div class="section-porsi">
                            Porsi
                            <div class="value">{{ $data->jumlah_paket ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-auto status-container text-end">
                        @php
                            switch ($data->status_distribusi) {
                                case 1:
                                    $badge = 'bg-success';
                                    $status = 'Terkirim';
                                    break;
                                case 0:
                                    $badge = 'bg-warning';
                                    $status = 'Dalam Perjalanan';
                                    break;
                                default:
                                    $badge = 'bg-danger';
                                    $status = 'Belum Diterima';
                                    break;
                            }
                        @endphp
                        
                        <span class="badge {{ $badge }}">{{ $status }}</span><br>
                        
                        @if(!empty($data->bukti_pengiriman))
                            <a href="#"
                               class="bukti_pengiriman btn btn-sm btn-outline-primary"
                               id="{{ $data->id_distribusi }}"
                               target="_blank">
                               Lihat Bukti
                            </a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                Belum Ada Bukti
                            </button>
                        @endif

                        <br><br><span>
                            <a href="/distributor/pengiriman_distributor/{{ $data->id_distribusi }}/konfirmasi_pengiriman_distributor"
                               class="btn btn-sm btn-outline-primary">
                                Konfirmasi Pengiriman
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
                        
        {{-- Jika tidak ada data --}}
        @if($distribusi->isEmpty())
            <div class="alert alert-info text-center mt-3">
                Belum ada data distribusi hari ini.
            </div>
        @endif
    </div>
</div>


{{-- Modal Bukti Terima --}}
<div class="modal modal-blur fade" id="modal-buktipengiriman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 px-3 align-items-center">
                <h6 class="modal-title mb-0">Bukti Pengiriman</h6>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
            <div class="modal-body" id="loadbuktipengiriman">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $(".bukti_pengiriman").click(function(e){
            e.preventDefault(); // supaya tidak reload atau ke target _blank
            var id = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'/distributor/pengiriman_distributor/lihat_bukti_pengiriman',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadbuktipengiriman").html(respond);

                    // baru tampilkan modal setelah isi sudah dimasukkan
                    $("#modal-buktipengiriman").modal("show");
                },
                error: function(xhr, status, error) {
                    console.error("Gagal memuat bukti:", error);
                }
            });
        });
    });
</script>
@endpush