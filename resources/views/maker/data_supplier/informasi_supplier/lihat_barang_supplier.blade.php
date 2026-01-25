<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Barang</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barang_supplier as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_barang_supplier }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">Belum ada data barang supplier.</td>
            </tr>
        @endforelse
    </tbody>
</table>