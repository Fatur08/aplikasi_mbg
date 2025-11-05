@if($histori->isEmpty())
<div class="alert alert-outline-warning">
    <p>Data Belum Ada</p>
</div>
@endif
@foreach ($histori as $d)
<ul class="listview image-listview">
    <li>
        <div class="item">
            @php
                $path = Storage::url('uploads/absensi/'.$d->foto_in);
            @endphp
            <img src="{{ url($path) }}" alt="avatar" class="imaged w128" style="height:150px; margin-right:20px;">
            <div class="in">
                <div>
                    <b>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</b><br>
                    <span class="bagde {{ $d->jam_in < "07:00" ? "bg-success" : "bg-danger" }}">
                        {{ $d->jam_in }}
                    </span>
                    <span class="badge bg-primary">{{ $d->jam_out }}</span>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach