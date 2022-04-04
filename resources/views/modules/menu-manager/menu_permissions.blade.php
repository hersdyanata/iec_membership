@if(count($permissions) > 0)
    @foreach ($permissions as $r)
        @if ($loop->last)
            @php $koma = ''; @endphp
        @else
            @php $koma = ','; @endphp
        @endif

        {!! '<code>'.$r->map_perm_nama.'</code>'.$koma !!}
    @endforeach
@else
    <code>-</code>
@endif