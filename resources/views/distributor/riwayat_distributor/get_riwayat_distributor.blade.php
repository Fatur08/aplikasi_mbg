<?php
require '../vendor/autoload.php';// Autoload semua library composer
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');
?>
@if($data->isEmpty())
    <div class="alert alert-warning text-center mt-2">
        Tidak ada data distribusi pada periode ini.
    </div>
@else
    @foreach ($data as $index => $d)
        <div class="distribution-card">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-1">
                        <strong>{{ $index + 1 }}.</strong> {{ \Carbon\Carbon::parse($d->tanggal_distribusi)->translatedFormat('l, d F Y') }}
                    </h6>
                </div>
            </div>
            <hr style="margin: 6px 0;">
            <div class="row">
                <div class="col">
                    <div class="section-sekolah">
                        Sekolah
                        <div class="value">{{ $d->tujuan_distribusi }}</div>
                    </div>
                    <div class="section-menu">
                        Menu
                        <div class="value">{{ $d->menu_makanan }}</div>
                    </div>
                    <div class="section-porsi">
                        Porsi
                        <div class="value">{{ $d->jumlah_paket }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif