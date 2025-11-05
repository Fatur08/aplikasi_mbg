<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Bahan</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bahan_terpakai as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_bahan }}</td>
                <td>{{ $item->jumlah_bahan_menu }} {{ $item->satuan_bahan_menu }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada data bahan terpakai.</td>
            </tr>
        @endforelse
    </tbody>
</table>