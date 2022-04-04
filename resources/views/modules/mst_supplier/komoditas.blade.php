<ul>
    @foreach ($produk as $r)
        <li>{{ $r->komoditas_nama }}</li>
    @endforeach
</ul>