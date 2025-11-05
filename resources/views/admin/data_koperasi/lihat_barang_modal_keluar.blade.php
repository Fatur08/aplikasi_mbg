<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Sumber</th> {{-- opsional --}}
        </tr>
    </thead>
    <tbody>
        @forelse($barang_list as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                <td>Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                <td>{{ ucfirst($item->sumber_data ?? '') }}</td> {{-- supplier / modal_keluar --}}
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data barang supplier atau modal keluar.</td>
            </tr>
        @endforelse
    </tbody>
</table>