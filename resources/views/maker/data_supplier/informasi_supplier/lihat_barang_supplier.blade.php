<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barang_supplier as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_barang_supplier }}</td>
                <td>{{ $item->jumlah_barang_supplier }} {{ $item->satuan_barang_supplier }}</td>
                <td>Rp {{ number_format($item->harga_barang_supplier, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada data barang supplier.</td>
            </tr>
        @endforelse
    </tbody>
</table>