@extends('layouts.owner.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Halaman
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="page-title mb-0">Informasi Stok Limit</h2>
                </div>
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
                        <table class="table table-bordered">
                            <thead>
                                <th>No.</th>
                                <th>Nama Bahan</th>
                                <th>Sisa</th>
                                <th>Kadaluarsa</th>
                                <th>Ket.</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                @foreach($stokLimit as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_stok }}</td>
                                    <td>{{ $item->sisa_stok }} {{ $item->satuan_stok }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa_stok)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if($item->status_stok == 0)
                                            <button class="btn btn-light btn-sm">Habis</button>
                                        @elseif($item->status_stok == 1)
                                            <button class="btn btn-secondary btn-sm">Hampir Habis</button>
                                        @elseif($item->status_stok == 2)
                                            <button class="btn btn-primary btn-sm">Tersedia</button>
                                        @elseif($item->status_stok == 3)
                                            <button class="btn btn-warning btn-sm">Hampir Kadaluarsa</button>
                                        @elseif($item->status_stok == 4)
                                            <button class="btn btn-orange btn-sm">Hampir Habis dan Hampir Kadaluarsa</button>
                                        @elseif($item->status_stok == 5)
                                            <button class="btn btn-danger btn-sm">Kadaluarsa</button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="edit_laporan_distribusi btn btn-info btn-sm" id="" >
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                Edit
                                            </a>
                                            <form action="/owner/laporan/distribusi//delete_laporan_distribusi" style="margin-left: 5px;" method="POST">
                                                @csrf
                                                <a class="btn btn-danger btn-sm delete-confirm-kepaladapur" >
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                    Hapus
                                                </a>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection