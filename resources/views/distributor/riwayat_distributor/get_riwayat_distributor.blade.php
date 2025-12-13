<?php
// require '/../vendor/autoload.php';
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
                <div class="col-auto status-container text-end">
                    @php
                        switch ($d->status_distribusi) {
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
                    
                    @if(!empty($d->bukti_pengiriman))
                        <a href="#"
                           class="bukti_pengiriman btn btn-sm btn-outline-primary"
                           id="{{ $d->id_distribusi }}"
                           target="_blank">
                           Lihat Bukti
                        </a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled>
                            Belum Ada Bukti
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif