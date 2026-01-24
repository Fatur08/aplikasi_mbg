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
        @forelse($barang_list as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                <td>Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data barang supplier atau modal keluar.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="table-light">
        <tr>
            <th colspan="2" class="text-end">Total</th>
            <th colspan="2">Rp {{ number_format($total_harganya, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>